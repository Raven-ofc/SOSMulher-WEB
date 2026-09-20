<?php

namespace Tests\Feature;

use App\Models\TbAgressor;
use App\Models\TbSolicitacao;
use App\Models\TbVitima;
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
        $v = TbVitima::create(['nomeVitima' => 'Pessoa de teste', 'cpfVitima' => '52998224725', 'emailVitima' => 'victim@example.test', 'dataNascimentoVitima' => '1990-01-01', 'statusVitima' => 'ativo']);
        $a = TbAgressor::create(['nomeAgressor' => 'Outro cadastro', 'cpfAgressor' => '11144477735', 'dataNascimentoAgressor' => '1990-01-01', 'statusAgressor' => 'ativo']);

        return [$v, $a];
    }

    private function casePayload($v, $a): array
    {
        return ['vitima_id' => $v->id, 'agressor_id' => $a->id, 'tipo' => 'Ocorrência manual', 'gravidade' => 'alta', 'data_hora' => now()->subHour()->format('Y-m-d\TH:i'), 'local' => 'Local informado no teste', 'bairro' => 'Bairro de teste'];
    }

    public function test_people_crud_validation_search_and_no_device_requirement(): void
    {
        $this->login();
        $v = ['nome' => 'Pessoa de teste', 'cpf' => '529.982.247-25', 'data_nascimento' => '1990-01-01', 'telefone' => '11987654321', 'email' => 'victim@example.test'];
        $this->post(route('admin.vitimas.salvar'), $v)->assertSessionHasNoErrors()->assertRedirect();
        $id = TbVitima::firstOrFail()->id;
        $this->assertDatabaseHas('tbtelefonevitima', ['idVitima' => $id, 'numeroTelefoneVitima' => '11987654321']);
        $this->post(route('admin.vitimas.salvar'), $v)->assertSessionHasErrors(['cpf', 'email']);
        $this->post(route('admin.vitimas.salvar'), array_replace($v, ['cpf' => '11111111111', 'email' => 'other@example.test']))->assertSessionHasErrors('cpf');
        $this->patch(route('admin.vitimas.atualizar', $id), array_replace($v, ['nome' => 'Nome editado']))->assertSessionHasNoErrors();
        $this->get(route('admin.vitimas.index', ['busca' => 'Nome editado']))->assertOk()->assertSee('Nome editado');
        $this->get(route('admin.vitimas.editar', $id))->assertOk();
        $this->get(route('admin.vitimas.visualizar', $id))->assertOk();
        $this->patch(route('admin.vitimas.status', $id), ['status' => 'inativo'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tbvitima', ['id' => $id, 'statusVitima' => 'inativo']);
        $this->post(route('admin.agressores.salvar'), array_replace($v, ['cpf' => '11144477735']))->assertSessionHasNoErrors();
        $this->assertNull(TbAgressor::firstOrFail()->idTornozeleira);
        $this->get(route('admin.agressores.visualizar', TbAgressor::first()->id))->assertOk();
    }

    public function test_occurrence_report_is_transactional_and_cannot_be_repeated(): void
    {
        $user = $this->login();
        [$v,$a] = $this->people();
        $this->post(route('admin.ocorrencias.salvar'), $this->casePayload($v, $a))->assertSessionHasNoErrors();
        $id = DB::table('tbocorrencia')->value('id');
        $this->patch(route('admin.vitimas.status', $v->id), ['status' => 'inativo'])->assertSessionHasErrors('status');
        $this->get(route('admin.ocorrencias.visualizar', $id))->assertOk();
        $this->get(route('admin.ocorrencias.relatorio', $id))->assertOk();
        $payload = ['inicio' => now()->subMinutes(50)->format('Y-m-d\TH:i'), 'fim' => now()->subMinutes(10)->format('Y-m-d\TH:i'), 'relato' => 'Relatório de teste com informações de atendimento.'];
        $this->post(route('admin.ocorrencias.finalizar', $id), array_replace($payload, ['fim' => now()->subDays(2)->format('Y-m-d\TH:i')]))->assertSessionHasErrors('fim');
        $this->post(route('admin.ocorrencias.finalizar', $id), $payload)->assertSessionHasNoErrors()->assertRedirect(route('admin.relatorios.visualizar', $id));
        $this->assertDatabaseHas('relatorios_atendimento', ['ocorrencia_id' => $id, 'user_id' => $user->id]);
        $this->post(route('admin.ocorrencias.finalizar', $id), $payload)->assertStatus(409);
        $this->assertDatabaseCount('relatorios_atendimento', 1);
        $this->get(route('admin.relatorios.visualizar', $id))->assertOk()->assertSee($payload['relato']);
        $this->get(route('painel'))->assertOk();
        $this->get(route('admin.estatisticas'))->assertOk()->assertSee('Ocorrência manual');
    }

    public function test_safe_place_approval_repetition_and_removal(): void
    {
        $user = $this->login();
        [$v] = $this->people();
        $payload = ['vitima_id' => $v->id, 'rotulo' => 'Local de teste', 'logradouro' => 'Rua de teste', 'numero' => '1', 'bairro' => 'Bairro', 'cidade' => 'Cidade', 'uf' => 'SP', 'cep' => '01000-000', 'latitude' => -23.5, 'longitude' => -46.6];
        $response = $this->post(route('admin.locais-seguros.salvar'), $payload)->assertSessionHasNoErrors()->assertRedirect();
        $id = TbSolicitacao::latest('id')->first()->id;
        $this->patch(url('administracao/locais-seguros/' . $id . '/decidir'), ['decisao' => 'aprovado'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tbsolicitacao', ['id' => $id, 'analisadoPor' => $user->id, 'statusSolicitacao' => 'aprovado']);
        $this->get(route('admin.vitimas.visualizar', $v->id))->assertOk()->assertSee('Local de teste');
        $this->patch(url('administracao/locais-seguros/' . $id . '/decidir'), ['decisao' => 'reprovado'])->assertStatus(409);
        $this->delete(url('administracao/locais-seguros/' . $id))->assertSessionHasNoErrors();
        $this->assertNotNull(DB::table('tbsolicitacao')->value('removidoEm'));
    }

    public function test_profile_validation_photo_and_cannot_edit_another_user(): void
    {
        Storage::fake('local');
        $user = $this->login();
        $other = User::factory()->create();
        $this->patch(route('admin.perfil.atualizar'), ['nome' => 'Nome editado', 'cpf' => '00000000000'])->assertSessionHasErrors('cpf');
        $this->patch(route('admin.perfil.atualizar'), ['id' => $other->id, 'nome' => 'Nome editado', 'telefone' => '11987654321', 'foto' => UploadedFile::fake()->createWithContent('avatar.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII='))])->assertSessionHasNoErrors();
        $this->assertSame('Nome editado', $user->fresh()->name);
        $this->assertNotSame('Nome editado', $other->fresh()->name);
        Storage::disk('local')->assertExists($user->fresh()->photo_path);
        $this->get(route('admin.perfil.foto'))->assertOk();
        $this->get(route('admin.perfil'))->assertOk();
    }

    public function test_forms_load_and_guests_are_blocked_and_original_device_api_is_available(): void
    {
        $this->get(route('admin.vitimas.index'))->assertRedirect(route('login'));
        $this->post(route('admin.vitimas.salvar'), [])->assertRedirect(route('login'));
        $this->getJson('/api/tornozeleiras')->assertOk()->assertExactJson([]);
        $this->postJson('/api/tornozeleiras/1/localizacoes', [])->assertNotFound();
        $this->login();
        foreach (['admin.vitimas.index', 'admin.vitimas.criar', 'admin.agressores.index', 'admin.agressores.criar', 'admin.vitimas.solicitacoes', 'admin.locais-seguros.criar', 'admin.ocorrencias.index', 'admin.ocorrencias.criar', 'admin.relatorios.index', 'admin.perfil', 'admin.perfil.editar', 'painel', 'admin.estatisticas'] as $name) {
            $this->get(route($name))->assertOk();
        }
    }

    public function test_future_occurrence_is_explained_and_not_saved(): void
    {
        $this->login();
        [$victim, $aggressor] = $this->people();
        $payload = $this->casePayload($victim, $aggressor);
        $payload['data_hora'] = now()->addDays(3)->format('Y-m-d\TH:i');
        $this->from(route('admin.ocorrencias.criar'))
            ->post(route('admin.ocorrencias.salvar'), $payload)
            ->assertRedirect(route('admin.ocorrencias.criar'))
            ->assertSessionHasErrors([
                'data_hora' => 'A data e hora da ocorrência não podem estar no futuro.',
            ]);
        $this->assertDatabaseCount('tbocorrencia', 0);
        $this->assertDatabaseCount('administration_audit', 0);
    }
}