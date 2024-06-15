<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3>Seus últimos contatos</h3>

                    @if($contacts->isEmpty())
                        <p>Você ainda não tem contatos cadastrados.</p>
                    @else
                        <ul class="list-group">
                            @foreach($contacts as $contact)
                                <li class="list-group-item">
                                    <strong>{{ $contact->nome }}</strong><br>
                                    CPF: {{ $contact->cpf }}<br>
                                    Telefone: {{ $contact->fone }}<br>
                                    Endereço: {{ $contact->endereco }}<br>
                                    CEP: {{ $contact->cep }}<br>
                                    GPS: {{ $contact->gps }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <hr>

                    <h3>Adicionar novo contato</h3>

                    <form method="POST" action="{{ route('contacts.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="cpf">CPF</label>
                            <input type="text" name="cpf" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="fone">Telefone</label>
                            <input type="text" name="fone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" name="endereco" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="cep">CEP</label>
                            <input type="text" name="cep" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="gps">GPS</label>
                            <input type="text" name="gps" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Adicionar Contato</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
