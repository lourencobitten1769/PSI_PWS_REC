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
class AirplaneController extends BaseController implements ResourceControllerInterface
{
    /**
     * Returns index view with all records
     */
    public function index()
    {
        $airplanes = Airplane::all();
        return View::make('airplane.airplanesList', ['airplanes' => $airplanes]);
    }

    public function create()
    {
        return View::make('airplane.airplaneCreate');
    }

    /**
     * Receives new record data through POST method and stores it in the DB table
     */
    public function store()
    {
        //create new resource (activerecord/model) instance with data from POST
        //your form name fields must match the ones of the table fields
        $airplane = new Airplane(Post::getAll());

        //var_dump($airplanes);
        if($airplane->is_valid()){
            $airplane->save();
            Redirect::toRoute('airplane/index');
        } else {
            //redirect to form with data and errors
            Redirect::flashToRoute('airplane/create', ['airplane' => $airplane]);
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
        $airplane = Airplane::find([$id]);

        if (is_null($airplane)) {
            //TODO redirect to standard error page
        } else {
            return View::make('airplane.airplaneEdit', ['airplane' => $airplane]);
        }
    }


    /**
     * Receives record data through POST method and updates it in the DB table
     */
    public function update($id)
    {
        //find resource (activerecord/model) instance where PK = $id
        //your form name fields must match the ones of the table fields
        $airplane = Airplane::find([$id]);
        $airplane->update_attributes(Post::getAll());

        if($airplane->is_valid()){
            $airplane->save();
            Redirect::toRoute('airplane/index');
        } else {
            //redirect to form with data and errors
            Redirect::flashToRoute('airplane/edit', ['airplane' => $airplane]);
        }
    }


    /**
     * deletes the record where PK = $id
     */
    public function destroy($id)
    {
        $airport = Airport::find([$id]);
        $airport->delete();
        Redirect::toRoute('airport/index');
    }
}