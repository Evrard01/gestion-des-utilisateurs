<div class="col-12" x-data="dropdown">
    @if ($errors)
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('dropdown', () => ({
                    open: true,

                    toggle() {
                        this.open = !this.open
                    },
                }))
            })
        </script>
    @endif
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">

            <hr>
            @if ($searchMode)
                @if ($resultats->count() > 0)
                    <table class="table table-striped table-hover">
                        <thead>
                            <div class="row p-lg-1">
                                <div class="col-sm-4">
                                    <a href="{{ route('home') }}" class="btn btn-warning">
                                        Retour
                                    </a>
                                </div>
                                <div class="col-sm">
                                </div>
                            </div>
                            <tr>
                                <th scope="col-1">#</th>
                                <th scope="col-3">Nom</th>
                                <th scope="col-3">Prenom</th>
                                <th scope="col-4">email</th>
                                <th scope="col-1">Oprions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resultats as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->nom }}</td>
                                    <td>{{ $user->prenom }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <button data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                            class="btn btn-info rounded" wire:click.prevent='edit({{ $user }})'>
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-danger rounded"
                                            wire:click.prevent='delete({{ $user }})'>
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                @if ($users->count() > 0)
                    <table class="table table-striped table-hover">
                        <thead>
                            <div class="row p-lg-1">
                                <div class="col-sm-4">
                                    <button @click="toggle()" type="button" class="btn btn-primary"
                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        Ajouter un utilisateur
                                    </button>
                                </div>
                                <div class="col-sm">
                                    <form>
                                        <div class="form-outline d-flex" style="">
                                            <input type="text" wire:model='search' id="search" name="search"
                                                placeholder="Faite une recherche de nom @error('search') {{ $message }} @enderror"
                                                id="form1" class="form-control " />
                                            <button type="button" wire:click.prevent='reserche()'
                                                class="btn btn-primary" style="">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <tr>
                                <th scope="col-1">#</th>
                                <th scope="col-3">Nom</th>
                                <th scope="col-3">Prenom</th>
                                <th scope="col-4">email</th>
                                <th scope="col-1">Oprions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->nom }}</td>
                                    <td>{{ $user->prenom }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <button data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                            class="btn btn-info rounded"
                                            wire:click.prevent='edit({{ $user }})'>
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-danger rounded"
                                            wire:click.prevent='delete({{ $user }})'>
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $users->links() }}
                    </div>
                @else
                    <h3>Aucun utilisateur pour le moment. Veuillez en creer</h3>
                @endif
            @endif
        </div>
        <div class="col-sm-2"></div>
    </div>
    <div x-show="open" class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        {{ $updateUser ? 'Modification d\'un utilisateur' : 'Ajout d\'un utilisateur' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click.prevent='resetFilters()'></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="Nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" wire:model='nom'
                                placeholder="Taper ici votre nom">
                            @error('nom')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prenom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                wire:model='prenom' placeholder="Taper ici votre prenom">
                            @error('prenom')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                wire:model='email' placeholder="Taper ici votre email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (!$updateUser)
                            <div class="mb-3 form-check">
                                Le mot de passe par defaut est : <span>password</span>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="reset" wire:click.prevent='resetFilters()' class="btn btn-danger"
                            data-bs-dismiss="modal">Annuler</button>
                        @if ($updateUser)
                            <button type="button" wire:click.prevent='update()'
                                class="btn btn-info">Modifier</button>
                        @else
                            <button type="button" wire:click.prevent='store()'
                                class="btn btn-success">Enregistrer</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
