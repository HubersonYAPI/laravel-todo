@extends ('layouts.app')

@section ('content')

    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs">
                <a name="" id="" class="btn btn-primary m-2" href="{{ route('todos.create') }}" role="button">Ajouter une todo</a>
            </div>

            <div class="col-xs">
                @if (Route::currentRouteName()=='todos.index')
                <a name="" id="" class="btn btn-warning m-2" href="{{ route('todos.undone') }}" role="button">Voir les todos ouvertes</a>
            </div>
            <div class="col-xs">
                <a name="" id="" class="btn btn-success m-2" href="{{ route('todos.done') }}" role="button">Voir les todos terminées</a>
                @elseif (Route::currentRouteName()=='todos.done')
                <a name="" id="" class="btn btn-warning m-2" href="{{ route('todos.index') }}" role="button">Voir toutes les todos</a>
            </div>
            <div class="col-xs">
                <a name="" id="" class="btn btn-success m-2" href="{{ route('todos.undone') }}" role="button">Voir les todos ouvertes</a>
                @elseif (Route::currentRouteName()=='todos.undone')
                <a name="" id="" class="btn btn-warning m-2" href="{{ route('todos.index') }}" role="button">Voir toutes les todos</a>
            </div>
            <div class="col-xs">
                <a name="" id="" class="btn btn-success m-2" href="{{ route('todos.done') }}" role="button">Voir les todos terminées</a>
                @endif
            </div>
        </div>
    </div> --}}

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs">
                <a name="" id="" class="btn btn-warning m-2" href="{{ route('todos.create') }}" role="button">Ajouter une todo</a>
            </div>

            @if (Route::currentRouteName()=='todos.index')
                <div class="col-xs">                
                    <a name="" id="" class="btn btn-primary m-2" href="{{ route('todos.undone') }}" role="button">Voir les todos ouvertes</a>
                </div>

                <div class="col-xs">
                    <a name="" id="" class="btn btn-success m-2" href="{{ route('todos.done') }}" role="button">Voir les todos terminées</a>
                </div>
                
            @elseif (Route::currentRouteName()=='todos.done')
                <div class="col-xs"> 
                <a name="" id="" class="btn btn-secondary m-2" href="{{ route('todos.index') }}" role="button">Voir toutes les todos</a>
                </div>

                <div class="col-xs">
                    <a name="" id="" class="btn btn-primary m-2" href="{{ route('todos.undone') }}" role="button">Voir les todos ouvertes</a>
                </div>

            @elseif (Route::currentRouteName()=='todos.undone')
                <div class="col-xs">
                    <a name="" id="" class="btn btn-secondary m-2" href="{{ route('todos.index') }}" role="button">Voir toutes les todos</a>
                </div>

                <div class="col-xs">
                <a name="" id="" class="btn btn-success m-2" href="{{ route('todos.done') }}" role="button">Voir les todos terminées</a>
                </div>
            @endif

            <div class="col-xs">
                <a name="" id="" class="btn btn-secondary m-2" href="{{ route('todos.create') }}" role="button">Voir mes todos créées</a>
            </div>
            
        </div>
    </div>

    @foreach ($datas as $data)
        <div class="alert alert-{{ $data->done ? 'success' : 'primary'}}" role="alert">
            <div class="row">
                <div class="col-sm">
                    <p class="my-0">
                        <strong><span class="badge badge-dark">#{{ $data->id }}</span></strong>
                        <small>
                            {{-- Afficher affecté à un utilisateur--}}
                            créée {{ $data->created_at->from() }} par 
                        
                            <strong>{{ Auth::user()->id == $data->user->id ? 'moi' : $data->user->name }}</strong> 

                            @if ($data->todoAffectedTo && $data->todoAffectedTo->id == Auth::user()->id)
                                affecté à<strong> moi</strong> 
                            @elseif ($data->todoAffectedTo)
                                {{ $data->todoAffectedTo ? ', Affectée à'.$data->todoAffectedTo->name: '' }}                            
                            @endif

                            {{-- Afficher affecté par un utilisateur --}}
                            @if ($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id == Auth::user()->id)
                                par <strong> moi même :D </strong>
                            @elseif ($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id != Auth::user()->id)
                                par <strong>{{ $data->todoAffectedBy->name }}</strong> 
                            @endif
                        </small> 

                        @if ($data->done)
                            <small>
                                <p>
                                    Terminé
                                    {{ $data->updated_at->from() }} - Terminée en
                                    {{ $data->updated_at->diffForHumans($data->created_at,1) }}
                                </p>
                            </small>                            
                        @endif
                    </p>
                    <details>
                        <summary>
                            <strong>{{ $data->name }}@if($data->done)<span class="badge badge-success">done</span>@endif</strong>
                        </summary>
                        <p>{{ $data->description }}</p>
                    </details>
                </div>
            
                <div class="col-sm form-inline justify-content-end my-1">
                    {{-- Button affected to --}}
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                    Affecter à
                                </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach ($users as $user)
                                <a href="/todos/{{ $data->id }}/affectedto/{{ $user->id }}" class="dropdown-item">{{ $user->name }}</a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Button done / undone --}}
                    @if ($data->done == 0)
                        <form action="{{ route('todos.makedone', $data->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <button type="submit" class="btn btn-success mx-1" style="min-width: 90px;">Done</button>
                        </form>                    
                    @else
                        <form action="{{ route('todos.makeundone', $data->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <button type="submit" class="btn btn-primary mx-1" style="min-width: 90px;">Undone</button>
                        </form>                     
                    @endif

                    {{-- Button edit --}}
                    @can('edit', $data)
                        <a name="" id="" class="btn btn-secondary mx-1" href="{{ route('todos.edit', $data->id) }}" role="button">Editer</a>
                    @elsecannot('edit', $data)
                        <a name="" id="" class="btn btn-secondary mx-1 disabled" href="{{ route('todos.edit', $data->id) }}" role="button">Editer</a>
                    @endcan

                    {{-- Button delete --}}
                    @can('delete', $data)
                        <form action="{{ route('todos.destroy', $data->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1">Effacer</button>
                        </form>
                    @elsecannot('delete', $data)
                        <form action="{{ route('todos.destroy', $data->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1" disabled>Effacer</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @endforeach 

    {{ $datas->links() }}

@endsection