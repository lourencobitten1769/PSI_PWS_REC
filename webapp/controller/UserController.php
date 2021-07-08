<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\View;

/**
 * CRUD Resource Controller for ActiveRecord Model Book
 *
 * Code generated by WebLogicMVC Code Builder
 *
 * Date: 2021-05-03
 *
 * WL Code Builder developed by Sílvio Priem Mendes
 * *
 */
class UserController extends BaseController implements ResourceControllerInterface
{
    /**
     * Returns index view with all records
     */
    public function index()
    {
        $users = User::all();
        return View::make('user.usersList', ['users' => $users]);
    }

    public function showGestorDeVoo(){
        $users=User::find_all_by_profile_id(3);
        return View::make('user.usersList',['users'=>$users]);
    }

    public function showOperadorDeCheckin(){
        $users=User::find_all_by_profile_id(4);
        return View::make('user.usersList',['users'=>$users]);
    }

    public function login(){
        return View::make('user.login');
    }

    public function processLogin(){
        $user = new User(Post::getAll());


        $userisset = User::find_by_username_and_password($user->username, md5($user->password));


        if (is_null($userisset)) {
            return View::make('user.login');
        } else {
            $_SESSION['id']= $userisset->user_id;
            $_SESSION['username'] = $user->username;
            $_SESSION['profile'] = $userisset->profile_id;

            if($_SESSION['profile']==1){
                Redirect::toRoute("airport/index");
            }
            else if($_SESSION['profile']==2){
                //$airport = Aeroporto::all();
                return View::make('home.index');
            }
            else if($_SESSION['profile']==3){
                //$airport = Aeroporto::all();
                Redirect::toRoute("airplane/index");
            }
        }
    }

    public function create()
    {
        return View::make('user.register');
    }

    /**
     * Receives new record data through POST method and stores it in the DB table
     */
    public function store()
    {
        //create new resource (activerecord/model) instance with data from POST
        //your form name fields must match the ones of the table fields
        $user = new User(Post::getAll());
        $user->points=0;
        $user->profile_id=2;
        $user->password=md5($user->password);
        var_dump($user);
        if($user->is_valid()){
            $user->save();
            Redirect::toRoute('user/login');
        } else {
            //redirect to form with data and errors
            Redirect::flashToRoute('user/create', ['user' => $user]);
        }
    }


    /**
     * return a detailed view with record where PK = $id
     */
    public function show($id)
    {
        $book = Book::find([$id]);

        if (is_null($book)) {
            //TODO redirect to standard error page
        } else {
            return View::make('book.show', ['book' => $book]);
        }
    }


    /**
     * return a editable form view with record where PK = $id
     */
    public function edit($id)
    {
        $book = Book::find([$id]);

        if (is_null($book)) {
            //TODO redirect to standard error page
        } else {
            return View::make('book.edit', ['book' => $book]);
        }
    }


    /**
     * Receives record data through POST method and updates it in the DB table
     */
    public function update($id)
    {
        //find resource (activerecord/model) instance where PK = $id
        //your form name fields must match the ones of the table fields
        $book = Book::find([$id]);
        $book->update_attributes(Post::getAll());

        if($book->is_valid()){
            $book->save();
            Redirect::toRoute('book/index');
        } else {
            //redirect to form with data and errors
            Redirect::flashToRoute('book/edit', ['book' => $book]);
        }
    }


    /**
     * deletes the record where PK = $id
     */
    public function destroy($id)
    {
        $book = book::find([$id]);
        $book->delete();
        Redirect::toRoute('book/index');
    }
}