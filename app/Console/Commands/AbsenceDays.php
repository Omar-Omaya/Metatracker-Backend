<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use App\Models\History;
use Carbon\Carbon;



class AbsenceDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abs:days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function createAbsDay(){
        // $historiesOfEmployees = History::with('Employee')->whereDate('created_at',Carbon::today())->get();
        $employees = Employee::get();
        foreach($employees as $employee){
            if(!History::whereDate('created_at',Carbon::today())->where('employee_id',$employee->id)->exists()){
                
                 Employee::create([
                    'lat' => 0,
                    'lng' => 0,
                    'employee_id' => $employee->id,
                    'out_of_zone' => 0,
                    'Start_time' => '0',
                    'End_time' => '0',
                    'Out_of_zone_time' => 0,
                    'is_absence' => 1,
  
                ]);
                


            }
        }



        
    } 
    public function handle()
    {
       
    }
}
