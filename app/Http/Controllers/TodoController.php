<?php

namespace App\Http\Controllers;

use App\Notifications\TodoAffected;
use App\Todo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    //Store all users
    public $users;

    public function __construct()
    {
        $this->users = User::getAllUsers();
    }

    




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $userId = Auth::user()->id;
        $datas = Todo::where(['affectedTo_id' => $userId])->orderBy('id', 'desc')->paginate(10);
        $users = $this->users;
        
        return view('todos.index', compact('datas', 'users'));

    }

    /** 
    *Affecter todo à un utlisateur
    */

    public function affectedTo(Todo $todo, User $user)
    {
        $todo->affectedTo_id = $user->id;
        $todo->affectedBy_id = Auth::user()->id;
        $todo->update();

        $user->notify(new TodoAffected($todo));

        return back();
    }

    /** 
    * Affiche les todos ouvertes
    */
    public function undone()
    {
        $datas = Todo::where('done', 0)->paginate(10);
        $users = $this->users;
        
        return view('todos.index', compact('datas', 'users'));
    }

    
    /** 
    * Affiche les todos terminées
    */
    public function done()
    {
        $datas = Todo::where('done', 1)->paginate(10);
        $users = $this->users;
        
        return view('todos.index', compact('datas', 'users'));
    }


    /** 
    *change le status de todo ouverte en todo terminée
    */
    public function makedone(Todo $todo)
    {
        $todo->done = 1;
        $todo->update();

        return back();
    }

    /** 
    *change le status de todo terminée en todo ouverte
    */
    public function makeundone(Todo $todo)
    {
        $todo->done = 0;
        $todo->update();

        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = new Todo();
        $todo->creator_id = Auth::user()->id;
        $todo->affectedTo_id = Auth::user()->id;
        $todo->name = $request->name;
        $todo->description = $request->description;
        $todo->save();

        notify()->success("La todo <span class='badge badge-dark'>#$todo->id</span> vient d'être créée.");
    
        return redirect()->route('todos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        if (!isset($request->done)) {
            $request['done']= 0 ;
        }
        $todo->update($request->all());
        notify()->success("La todo <span class='badge badge-dark'>#$todo->id</span> a bien été mise à jour.");

        return redirect()->route('todos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Todo  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        notify()->error("La todo <span class='badge badge-dark'>#$todo->id</span> à bien été supprimée.");

        return back();
    }
}
