<?php

namespace Tests\Feature;

use App\Models\tbagressor;
use App\Models\tbvitima;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdministrationTest extends TestCase
{
    use RefreshDatabase;

    private function login(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    private function people(): array
    {
        $v = tbvitima::create(['nomeVitima' => 'Pessoa de teste', 'cpfVitima' => '52998224725', 'emailVitima' => 'victim@example.test', 'dataNascimentoVitima' => '1990-01-01', 'statusVitima' => 'ativo']);
        $a = tbagressor::create(['nomeAgressor' => 'Outro cadastro', 'cpfAgressor' => '11144477735', 'dataNascimentoAgressor' => '1990-01-01', 'statusAgressor' => 'ativo']);

        return [$v, $a];
    }

    private function casePayload($v, $a): array
    {
        return ['victim_id' => $v->id, 'aggressor_id' => $a->id, 'type' => 'Ocorrência manual', 'severity' => 'alta', 'occurred_at' => now()->subHour()->format('Y-m-d\TH:i'), 'location' => 'Local informado no teste', 'district' => 'Bairro de teste'];
    }

    public function test_people_crud_validation_search_and_no_device_requirement(): void
    {
        $this->login();
        $v = ['name' => 'Pessoa de teste', 'cpf' => '529.982.247-25', 'birth_date' => '1990-01-01', 'phone' => '11987654321', 'email' => 'victim@example.test'];
        $this->post(route('admin.victims.store'), $v)->assertSessionHasNoErrors()->assertRedirect();
        $id = tbvitima::firstOrFail()->id;
        $this->assertDatabaseHas('tbtelefonevitima', ['idVitima' => $id, 'numeroTelefoneVitima' => '11987654321']);
        $this->post(route('admin.victims.store'), $v)->assertSessionHasErrors(['cpf', 'email']);
        $this->post(route('admin.victims.store'), array_replace($v, ['cpf' => '11111111111', 'email' => 'other@example.test']))->assertSessionHasErrors('cpf');
        $this->patch(route('admin.victims.update', $id), array_replace($v, ['name' => 'Nome editado']))->assertSessionHasNoErrors();
        $this->get(route('admin.victims', ['search' => 'Nome editado']))->assertOk()->assertSee('Nome editado');
        $this->get(route('admin.victims.edit', $id))->assertOk();
        $this->get(route('admin.victims.show', $id))->assertOk();
        $this->patch(route('admin.victims.status', $id), ['status' => 'inativo'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tbvitima', ['id' => $id, 'statusVitima' => 'inativo']);
        $this->post(route('admin.aggressors.store'), array_replace($v, ['cpf' => '11144477735']))->assertSessionHasNoErrors();
        $this->assertNull(tbagressor::firstOrFail()->idTornozeleira);
        $this->get(route('admin.aggressors.show', tbagressor::first()->id))->assertOk();
    }

    public function test_occurrence_report_is_transactional_and_cannot_be_repeated(): void
    {
        $user = $this->login();
        [$v,$a] = $this->people();
        $this->post(route('admin.occurrences.store'), $this->casePayload($v, $a))->assertSessionHasNoErrors();
        $id = DB::table('tbocorrencia')->value('id');
        $this->patch(route('admin.victims.status', $v->id), ['status' => 'inativo'])->assertSessionHasErrors('status');
        $this->get(route('admin.occurrences.show', $id))->assertOk();
        $this->get(route('admin.occurrences.finish', $id))->assertOk();
        $payload = ['start' => now()->subMinutes(50)->format('Y-m-d\TH:i'), 'end' => now()->subMinutes(10)->format('Y-m-d\TH:i'), 'report' => 'Relatório de teste com informações de atendimento.'];
        $this->post(route('admin.occurrences.complete', $id), array_replace($payload, ['end' => now()->subDays(2)->format('Y-m-d\TH:i')]))->assertSessionHasErrors('end');
        $this->post(route('admin.occurrences.complete', $id), $payload)->assertSessionHasNoErrors()->assertRedirect(route('admin.reports.show', $id));
        $this->assertDatabaseHas('relatorios_atendimento', ['ocorrencia_id' => $id, 'user_id' => $user->id]);
        $this->post(route('admin.occurrences.complete', $id), $payload)->assertStatus(409);
        $this->assertDatabaseCount('relatorios_atendimento', 1);
        $this->get(route('admin.reports.show', $id))->assertOk()->assertSee($payload['report']);
        $this->get(route('dashboard'))->assertOk();
        $this->get(route('admin.statistics'))->assertOk()->assertSee('Ocorrência manual');
    }

    public function test_safe_place_approval_repetition_and_removal(): void
    {
        $user = $this->login();
        [$v] = $this->people();
        $payload = ['victim_id' => $v->id, 'label' => 'Local de teste', 'street' => 'Rua de teste', 'number' => '1', 'district' => 'Bairro', 'city' => 'Cidade', 'state' => 'SP', 'postal_code' => '01000-000', 'latitude' => -23.5, 'longitude' => -46.6];
        $this->post(route('admin.places.store'), $payload)->assertSessionHasNoErrors();
        $id = DB::table('tbsolicitacao')->value('id');
        $this->patch(route('admin.places.decide', $id), ['decision' => 'aprovado'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tbsolicitacao', ['id' => $id, 'analisadoPor' => $user->id, 'statusSolicitacao' => 'aprovado']);
        $this->get(route('admin.victims.show', $v->id))->assertOk()->assertSee('Local de teste');
        $this->patch(route('admin.places.decide', $id), ['decision' => 'reprovado'])->assertStatus(409);
        $this->delete(route('admin.places.remove', $id))->assertSessionHasNoErrors();
        $this->assertNotNull(DB::table('tbsolicitacao')->value('removidoEm'));
    }

    public function test_profile_validation_photo_and_cannot_edit_another_user(): void
    {
        Storage::fake('local');
        $user = $this->login();
        $other = User::factory()->create();
        $this->patch(route('admin.profile.update'), ['name' => 'Nome editado', 'cpf' => '00000000000'])->assertSessionHasErrors('cpf');
        $this->patch(route('admin.profile.update'), ['id' => $other->id, 'name' => 'Nome editado', 'phone' => '11987654321', 'photo' => UploadedFile::fake()->createWithContent('avatar.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII='))])->assertSessionHasNoErrors();
        $this->assertSame('Nome editado', $user->fresh()->name);
        $this->assertNotSame('Nome editado', $other->fresh()->name);
        Storage::disk('local')->assertExists($user->fresh()->photo_path);
        $this->get(route('admin.profile.photo'))->assertOk();
        $this->get(route('admin.profile'))->assertOk();
    }

    public function test_forms_load_and_guests_are_blocked_and_device_api_is_removed(): void
    {
        $this->get(route('admin.victims'))->assertRedirect(route('login'));
        $this->post(route('admin.victims.store'), [])->assertRedirect(route('login'));
        $this->getJson('/api/tornozeleiras')->assertNotFound();
        $this->postJson('/api/tornozeleiras/1/localizacoes', [])->assertNotFound();
        $this->login();
        foreach (['admin.victims', 'admin.victims.create', 'admin.aggressors', 'admin.aggressors.create', 'admin.victims.requests', 'admin.places.create', 'admin.occurrences', 'admin.occurrences.create', 'admin.reports', 'admin.profile', 'admin.profile.edit', 'dashboard', 'admin.statistics'] as $name) {
            $this->get(route($name))->assertOk();
        }
    }

    public function test_future_occurrence_is_explained_and_not_saved(): void
    {
        $this->login();
        [$victim, $aggressor] = $this->people();
        $payload = $this->casePayload($victim, $aggressor);
        $payload['occurred_at'] = now()->addDays(3)->format('Y-m-d\TH:i');
        $this->from(route('admin.occurrences.create'))
            ->post(route('admin.occurrences.store'), $payload)
            ->assertRedirect(route('admin.occurrences.create'))
            ->assertSessionHasErrors([
                'occurred_at' => 'A data e hora da ocorrência não podem estar no futuro. Informe quando ela aconteceu.',
            ]);
        $this->assertDatabaseCount('tbocorrencia', 0);
        $this->assertDatabaseCount('administration_audit', 0);
    }
}
