<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CadastroUsuarioTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o cadastro de um novo usuário.
     *
     * @return void
     */
    public function testCadastroUsuario()
    {
        $dadosUsuario = [
            'nome' => 'Fulano de Tal',
            'email' => 'fulano@example.com',
            'senha' => 'senha123',
            'senha_confirmation' => 'senha123', // necessário para a confirmação de senha
        ];

        // Faz a requisição POST para a rota de cadastro de usuários
        $response = $this->postJson('/api/v1/novo-usuario', $dadosUsuario);

        // Verifica se o status HTTP retornado é 201 (Created)
        $response->assertStatus(201);

        // Verifica se o usuário foi inserido no banco de dados
        $this->assertDatabaseHas('users', ['email' => 'fulano@example.com']);
    }
}
