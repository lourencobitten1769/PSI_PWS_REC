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
class TicketController extends BaseController implements ResourceControllerInterface
{
    /**
     * Returns index view with all records
     */
    public function index()
    {
        $tickets = Ticket::find_all_by_user_id($_SESSION['id']);

        return View::make('ticket.ticketHistory', ['tickets' => $tickets]);
    }

    public function confirmTicket($flight_id){
        echo $flight_id;
        $user= User::find_by_user_id($_SESSION['id']);
        $flight=Flight::find_by_flight_id($flight_id);
        $origin = Airport::find_by_airport_id($flight->origin);
        $destination=Airport::find_by_airport_id($flight->destination);
        return View::make('ticket.ticketConfirm',['flight'=>$flight , 'origin'=>$origin , 'destination'=>$destination, 'user'=>$user]);
    }

    public function ticketsWithoutCheckin(){
        $tickets= Ticket::find_all_by_checkin("Não");
        return View::make('ticket.ticketForCheckin', ['tickets'=>$tickets]);
    }

    public function processCheckin($id){
        $ticket= Ticket::find_by_ticket_id($id);
        $ticket->checkin="Sim";
        if($ticket->round_trip=="Não"){
            $ticket->total_price= $ticket->flight->price_aditional_discount;
        }
        else{
            $ticket->total_price=$ticket->flight->price_aditional_discount * 2;
        }

        $user= User::find_by_user_id($ticket->user_id);
        $points=$ticket->flight->distance/100;
        $user->points=$user->points + $points;

        $ticket->save();
        $user->save();
        Redirect::toRoute('ticket/ticketsWithoutCheckin');
    }

    public function scales($flight_id){
        $flights = Flight::all();
        $origins = [];
        $destinations=[];
        foreach ($flights as $flight){
            $airport_origin=Airport::find_by_airport_id($flight->origin);
            array_push($origins, $airport_origin->airport_name);
            $airport_destination=Airport::find_by_airport_id($flight->destination);
            array_push($destinations,$airport_destination->airport_name);
        }
        $scales=Scale::find_all_by_flight_id($flight_id);
        return View::make('flight.flightList', ['flights' => $flights , 'origins'=>$origins, 'destinations'=>$destinations,'scales'=>$scales]);
    }

    public function create()
    {
        $airports= Airport::all();
        $airplanes= Airplane::all();
        return View::make('flight.flightCreate', ['airports' => $airports , 'airplanes' => $airplanes]);
    }

    /**
     * Receives new record data through POST method and stores it in the DB table
     */
    public function store()
    {

        //create new resource (activerecord/model) instance with data from POST
        //your form name fields must match the ones of the table fields
        $ticket = new Ticket();
        $ticket->purchase_date= date('Y-m-d');
        $ticket->checkin='Não';
        $ticket->round_trip=Post::get('roundtrip');
        if($ticket->round_trip=='Sim'){
            $ticket->total_price=Post::get('flight_price') * 2;
        }
        else{
            $ticket->total_price=Post::get('flight_price');
        }
        $ticket->flight_id=$_SESSION['flight_id'];
        $ticket->user_id=$_SESSION['id'];



        if($ticket->is_valid()){
            $ticket->save();
            Redirect::toRoute('flight/index');
        } else {
            //redirect to form with data and errors
            Redirect::flashToRoute('flight/confirmTicket', ['ticket' => $ticket]);
        }
    }

    /**
     * return a detailed view with record where PK = $id
     */
    public function show($id)
    {
        $ticket = Ticket::first($id);

        return View::make('ticket.ticketShow', ['ticket' => $ticket]);
    }


    /**
     * return a editable form view with record where PK = $id
     */
    public function edit($id)
    {
        $flight = Flight::find([$id]);
        $airplanes=Airplane::all();
        $airports=Airport::all();

        if (is_null($flight)) {
            //TODO redirect to standard error page
        } else {
            return View::make('flight.flightEdit', ['flight' => $flight , 'airplanes'=>$airplanes, 'airports'=>$airports]);
        }
    }


    /**
     * Receives record data through POST method and updates it in the DB table
     */
    public function update($id)
    {
        //find resource (activerecord/model) instance where PK = $id
        //your form name fields must match the ones of the table fields
        $flight = Flight::find([$id]);
        $flight->update_attributes(Post::getAll());

        if($flight->is_valid()){
            $flight->save();
            Redirect::toRoute('flight/index');
        } else {
            //redirect to form with data and errors
            Redirect::flashToRoute('flight/edit', ['flight' => $flight]);
        }
    }

    public function procurar(){

        $escolha=Post::get('tipoPesquisa');

        if($escolha=='origemDestino'){
            $flights = Flight::find_all_by_origin_and_destination(Post::get('origin'), Post::get('destination'));
            //var_dump($flights);
            $origins = [];
            $destinations=[];
            foreach ($flights as $flight){
                $airport_origin=Airport::find_by_airport_id($flight->origin);
                array_push($origins, $airport_origin->airport_name);
                $airport_destination=Airport::find_by_airport_id($flight->destination);
                array_push($destinations,$airport_destination->airport_name);
            }
            return View::make('flight.flightList', ['flights' => $flights , 'origins'=>$origins, 'destinations'=>$destinations]);
        }
        else{
            $flights = Flight::find_by_sql("SELECT * FROM `flights` WHERE DATE(date_hour_departure)= '". Post::get('date_hour_departure') ."'");
            $origins = [];
            $destinations=[];
            foreach ($flights as $flight){
                $airport_origin=Airport::find_by_airport_id($flight->origin);
                array_push($origins, $airport_origin->airport_name);
                $airport_destination=Airport::find_by_airport_id($flight->destination);
                array_push($destinations,$airport_destination->airport_name);
            }
            return View::make('flight.flightList', ['flights' => $flights , 'origins'=>$origins, 'destinations'=>$destinations]);

        }

    }


    /**
     * deletes the record where PK = $id
     */
    public function destroy($id)
    {
        $flight= Flight::find([$id]);
        $flight->delete();
        Redirect::toRoute('flight/index');
    }
}