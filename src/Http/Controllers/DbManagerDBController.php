<?php

namespace PreciousGariya\DbManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PDO;

class DbManagerDBController extends Controller
{
    public function index()
    {
        $permissions = ["SELECT", "INSERT", "UPDATE", "DELETE"];
        return view('db_manager::setup', compact('permissions'));
    }

    public function step_one()
    {
        // dd(Session::get('rootUser'));
        return view('db_manager::include.step_1');
    }

    public function step_one_post(Request $request)
    {
        $validated = $request->validate([
            'user' => 'required',
            'password' => 'required',
        ]);


        $d = $this->dB_user_setup($validated['user'], $validated['password']);

        $database = DB::connection('root_access');
        $check = $this->checkDBconnection();

        if ($check) {
            $tables = $database->select('SHOW TABLES');

            Session::put('rootUser', [
                'user' => encryptthis($validated['user'], 'root_access'),
                'pass' => encryptthis($validated['password'], 'root_access')
            ]);
            // Extract the table names from the response object
            $tableNames = array_map('current', $tables);
            $permissions = ["SELECT", "INSERT", "UPDATE", "DELETE"];
            $response = view('db_manager::include.step_2', compact('tableNames', 'permissions'));
        } else {
            $response = back()->with('error', 'Opps!!! Invalid Credentials');
        };
        return $response;
    }


    public function step_two(Request $request)
    {   
        // if($request->permissions == null){
        //      $tables = $database->select('SHOW TABLES');
        //     return view('db_manager::include.step_2')->with('error','min 1 req');
        //    }

        if (Session::has('rootUser')) {
            $data = Session::get('rootUser');
        } else {
            return redirect()->route('step_1')->with('error', 'Login Required');
        }
        // dd($data);
        $this->dB_user_setup(decryptthis($data['user'], 'root_access'), decryptthis($data['pass'], 'root_access'));
        $this->checkDBconnection();
        $database = DB::connection('root_access');

        if (count($request->permissions) != 4) {
            $revoke = $this->revokeAllPrivileges(env('DB_USERNAME'));
            if ($revoke == 'true') {
                $grantcheck = $this->checkAllGrant(env('DB_USERNAME'));
                if ($grantcheck != 'true') {
                    dd($grantcheck);
                }
                $giveAllNewPermissions = $this->giveAllNewPermissions(env('DB_USERNAME'), $request->table);
                if ($giveAllNewPermissions == 'true') {
                    //giving selected permission to selected Table
                    $giveSelectedPerms = $this->giveSelectedPermitOnSelectedTable($request->permissions, $request->table);
                    if ($giveSelectedPerms == 'true') {
                        echo "---------FLUSHING PRIVILEGES-----------";
                        echo "<br>";
                        echo "<br>";
                        if ($database->statement("FLUSH PRIVILEGES") == 'true') {
                            echo "---------PRIVILEGES FLUSHED-----------";
                            echo "<br>";
                            echo "<br>";
                        }
                    }
                }
            } else {
                dd($revoke);
            }
            Session::forget('rootUser');
        }

        return redirect()->route('db-manager.index')->with('success', 'Successfully Completed!!!');
    }
    /**
     * Show the form for creating a new resource.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function giveSelectedPermitOnSelectedTable($permissions, $table)
    {
        try {
            $database = DB::connection('root_access');
            $db_user = env('DB_USERNAME');
            $db_name = env('DB_DATABASE');
            foreach ($permissions as $key => $permission) {
                echo "---------$permission ---PERMISSION GRANTING-----------";
                echo "<br>";
                echo "<br>";
                $database->statement("GRANT $permission ON $db_name.$table TO '$db_user'@'localhost';");
                echo "---------$permission ---PERMISSION GRANTED-----------";
                echo "<br>";
                echo "<br>";
            }
            $status = true;
        } catch (\Exception $e) {
            $status = $e->getMessage();
        }
        return $status;
    }

    public function giveAllNewPermissions($currentDbUser, $excludeTable)
    {
        try {
            echo "---------Working-----------";
            echo "<br>";
            echo "<br>";
            echo '<br>';
            $database = DB::connection('root_access');
            $db_name = env('DB_DATABASE');

            $timestamp = date('Y-m-d_H-i-s');
            $filename = "/var/lib/mysql-files/mytestfile_db_$timestamp.sql";
            // Build the SQL query
            $sql = "SELECT CONCAT('GRANT ALL PRIVILEGES ON ', table_schema, '.', table_name, ' TO \'$currentDbUser\'@\'localhost\';') INTO OUTFILE '$filename' FROM information_schema.TABLES WHERE table_schema = '$db_name' AND table_name <> '$excludeTable'";

            // Execute the query
            echo "---------LOADING-----------";
            echo "<br>";
            echo "<br>";
            echo '<br>';
            $sql_run = $database->statement($sql);

            echo "---------GENERATING QUERIES FOR YOUR REQUIREMENTS-----------";
            echo "<br>";
            echo "<br>";
            echo '<br>';

            echo "----------LOADING GENERATED QUERIES----------";
            echo "<br>";
            echo "<br>";
            echo '<br>';

            $output = $database->select("SELECT LOAD_FILE('$filename') AS output")[0]->output;
            echo "----------LOADED GENERATED QUERIES----------";
            echo "<br/>";
            echo "<br/>";
            echo '<br>';
            $sys_gen_queries = explode(';', $output);
            $count = count($sys_gen_queries);
            echo "----------TOTAL $count GENERATED QUERIES----------";
            echo '<br>';
            echo '<br>';
            echo '<br>';

            echo "----------RUNNING GENERATED QUERIES----------";
            echo '<br>';
            echo '<br>';
            echo '<br>';
            foreach ($sys_gen_queries as $key => $sysQuery) {
                if ($sysQuery != "\n")
                    $database->statement($sysQuery);
            }
            $status = true;
        } catch (\Exception $e) {
            $status = $e->getMessage();
        }

        return $status;
    }
    public function checkAllGrant($currentDbUser)
    {
        try {
            $database = DB::connection('root_access');
            $grants = $database->select("SHOW GRANTS FOR '$currentDbUser'@'localhost'");
            $status = true;
        } catch (\Exception $e) {
            $status = $e->getMessage();
            $status = false;
        }
        return $status;
        // dd($grants); to be work further

    }
    public function revokeAllPrivileges($currentDbUser)
    {

        try {
            $database = DB::connection('root_access');
            $database->statement("REVOKE ALL PRIVILEGES ON *.* FROM '$currentDbUser'@'localhost'");
            $status = true;
        } catch (\Exception $e) {
            $status = $e->getMessage();
        }
        return $status;
    }

    public function dB_user_setup($root, $password)
    {
        if (config()->has('database.connections.root_access')) {
            config(['database.connections.root_access.username' => $root]);
            config(['database.connections.root_access.password' => $password]);
        } else {
            back()->with('error', 'Opps!!! Setup Not Completed yet, run composer dump-autoload');
        }
    }
    public function checkDBconnection()
    {
        try {
            $pdo = DB::connection('root_access')->getPdo();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
            $data = $e->getMessage();
        }
        return $status;
    }
    /**
     * Display the specified resource.
     */
}
