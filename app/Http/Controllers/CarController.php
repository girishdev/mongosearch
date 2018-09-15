<?php

namespace App\Http\Controllers;

set_time_limit(600);

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Car;
use Session;
use Excel;
use File;

class CarController extends Controller
{

    /**
     * list the Car Information
     *
     * @return void
     */
    public function index()
    {
        $cars=Car::all();
        return view('carindex',compact('cars'));
    }

    /**
     * To Show the view
     *
     * @return void
     */
    public function create()
    {
        return view('carcreate');
    }

    /**
     * To Store the data to DB
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $car = new Car();
        $car->carcompany = $request->get('carcompany');
        $car->model = $request->get('model');
        $car->price = $request->get('price');
        $car->save();
        return redirect('car')->with('success', 'Car has been successfully added');
    }

    /**
     * updating the Car Information
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $car = Car::find($id);
        return view('caredit',compact('car','id'));
    }

    public function update(Request $request, $id)
    {
        $car= Car::find($id);
        $car->carcompany = $request->get('carcompany');
        $car->model = $request->get('model');
        $car->price = $request->get('price');
        $car->save();
        return redirect('car')->with('success', 'Car has been successfully update');
    }

    public function destroy($id)
    {
        $car = Car::find($id);
        $car->delete();
        return redirect('car')->with('success','Car has been  deleted');
    }

    // New Addons:

    public function recordIndex()
    {
        return view('insert');
    }

    public function import(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file' => 'required'
        ));

        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file->getRealPath();
                $start = microtime(true);
                $data = Excel::load($path, function($reader) {
                })->get();

                if(!empty($data) && $data->count()){

                    foreach ($data as $key => $value) {
                        $insert[] = [
                            'modele' => $value->modele,
                            'sousfamille' => $value->sousfamille,
                            'photo' => $value->photo,
                        ];
                    }

                    if(!empty($insert)){
                        $insertData = DB::collection('celio')->insert($insert);
                        // If it is for 100 Record - Collection_Input100.xlsx
                        // This is only for "Inserting Data" - 0.005395174026489258
                        // This is "Reading file" and "Inserting Data" - 23.27740788459778
                        if($insertData){
                            $end = microtime(true);
                            $time = $end - $start;
                            Session::flash('success', "Your Data has successfully imported Time taken: $time");
                        } else {
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
                    }
                }

                /*
                if(!empty($data) && $data->count()){

                    foreach ($data as $key => $value) {
                        $insert[] = [
                            'carcompany' => $value->Département,
                            'model' => $value->Famille,
                            'price' => $value->Sousfamille,
                        ];
                    }

                    if(!empty($insert)){
                        $insertData = DB::table('car')->insert($insert);
                        if ($insertData) {
                            Session::flash('success', 'Your Data has successfully imported');
                        }else {
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
                    }
                } /**/

                return back();

            } else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }

    public function listall(Request $request)
    {
        // List All/Get All
        return $users = DB::collection('car')->get();
        // return $users = DB::collection('users')->get();

        // search Query:
        // $name = 'Kumar';
        // $users = DB::collection('users')->where('name',$name)->first();

        // Delete Query:
        // $result = DB::collection('users')->where('name', $name)->delete();

        // Update Query:
        $name = 'Girish';
        $price = '125 Rs';
        return $result = DB::collection('car')->where('carcompany', $name)->update(['price' => $price]);

    }

    public function funUbuntu(Type $var = null)
    {
        # code...
    }
}
