<?php

class LoginController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Login');

    }

    public function index()
    {
        print ('Estoy en loginController<br>');
        $data = [
            'titulo' => 'Login',
            'menu'   => false,
        ];
        $this->view('login', $data);
    }
    public function olvido()
    {
        $errors = [];

       if ($_SERVER['REQUEST_METHOD'] != 'POST')
       {
           $data = [
	       'titulo' => 'Olvido de la contraseña',
               'menu' => false,
               'errors' => $errors,
               'subtitle'=>'Olvidaste la contraseña?'];
           $this->view('olvido', $data);
       }
       else
       {
           $email = $_POST['email'] ?? '';

           if ($email == '') {
               array_push($errors, 'El email es requerido');
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL))
           {
               array_push($errors, 'El email no es valido');
           }
           if (count($errors)==0)
           {
               if (!$this->model->existsEmail($email))
               {
                   array_push($errors, 'El email no existe en la base de datos');
               }
               else
               {
                   if ($this->model->sendEmail($email))
                   {
                       $data = [
                           'titulo'=>'Cambio de contraseña de acceso',
                           'menu' => false,
                           'errors' => [],
                           'subtitle'=>'Cambio de contraseña de acceso',
                           'text' => 'Se ha enviado un correo a <b>.$email.</b> para que pueda cmabiar su clave de acceso. <b>No olvide revisa su carpeta de spam.<b/>',
                           'color'=>'alert-success',
                           'url'=>'login',//para desarrollar menuController
                           'colorButton' => 'btn-success',
                           'textButton' => 'Regresar',
                           ];
                   }
                   else
                   {
                       $data = [
                           'titulo'=>'Error con correo',
                           'menu' => false,
                           'errors' => [],
                           'subtitle'=>'Error envio correo electronico',
                           'text' => 'Problema al enviar correo electronico.<b>Por favor pruebe mas tarde',
                           'color'=>'alert-danger',
                           'url'=>'menu',//para desarrollar menuController
                           'colorButton' => 'btn-danger',
                           'textButton' => 'Regresar',
                           ];

                       $this->view('mensaje', $data);
                   }
               }
           }
           if (count($errors)>0)
           {
               ['titulo' => 'Olvido de la contraseña',
                   'menu' => false,
                   'errors' => $errors,
                   'subtitle'=>'Olvidaste la contraseña?'];
               $this->view('olvido', $data);
           }
       }
    }

    public function registro()
    {
        $errors = [];
        $dataForm = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesamos la información recibida del formulario
            $firstName = $_POST['first_name'] ?? '';
            $lastName1 = $_POST['last_name_1'] ?? '';
            $lastName2 = $_POST['last_name_2'] ?? '';
            $email = $_POST['email'] ?? '';
            $password1 = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';
            $address = $_POST['address'] ?? '';
            $city = $_POST['city'] ?? '';
            $state = $_POST['state'] ?? '';
            $postcode = $_POST['postcode'] ?? '';
            $country = $_POST['country'] ?? '';

            $dataForm = [
                'firstName' => $firstName,
                'lastName1' => $lastName1,
                'lastName2' => $lastName2,
                'email' => $email,
                'password' => $password1,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'postcode' => $postcode,
                'country' => $country
            ];

            if ($firstName == '') {
                array_push($errors, 'El nombre es requerido');
            }
            if ($lastName1 == '') {
                array_push($errors, 'El primer apellido es requerido');
            }
            if ($lastName2 == '') {
                array_push($errors, 'El segundo apellido es requerido');
            }
            if ($email == '') {
                array_push($errors, 'El email es requerido');
            }
            if ($password1 == '') {
                array_push($errors, 'La contraseña es requerido');
            }
            if ($password2 == '') {
                array_push($errors, 'Repetir contraseña es requerido');
            }
            if ($address == '') {
                array_push($errors, 'La dirección es requerida');
            }
            if ($city == '') {
                array_push($errors, 'La ciudad es requerida');
            }
            if ($state == '') {
                array_push($errors, 'La provincia es requerida');
            }
            if ($postcode == '') {
                array_push($errors, 'El código postal es requerido');
            }
            if ($country == '') {
                array_push($errors, 'El país es requerido');
            }
            if ($password1 != $password2) {
                array_push($errors, 'Las contraseñas deben ser iguales');
            }

            if (count($errors) == 0) {

                if ($this->model->createUser($dataForm))
                {
                    $data = [
                        'titulo'=>'Bienvenido',
                        'menu' => false,
                        'errors' => [],
                        'subtitle'=>'Bienvenido/a a nuestra tienda online',
                        'text' => 'Gracias por su registro',
                        'color'=>'alert-success',
                        'url'=>'menu',//para desarrollar menuController
                        'colorButton' => 'btn-success',
                        'textButton' => 'Acceder',
                    ];
                    $this->view('mensaje', $data);
                }
                else
                {
                    $data = [
                        'titulo'=>'Error',
                        'menu' => false,
                        'errors' => [],
                        'subtitle'=>'Error en el proceso de registro',
                        'text' => 'Su correo utilizado ya esta en uso',
                        'color'=>'alert-danger',
                        'url'=>'login',//para desarrollar menuController
                        'colorButton' => 'btn-danger',
                        'textButton' => 'Regresar',
                    ];
                    $this->view('mensaje', $data);
                }

            } else {
                $data = [
                    'titulo' => 'Registro',
                    'menu' => false,
                    'errors' => $errors,
                    'dataForm' => $dataForm
                ];

                $this->view('register', $data);
            }
        }
        else
        {
            // Mostramos el formulario
            $data = [
                'titulo' => 'Registro',
                'menu' => false,
            ];

            $this->view('register', $data);
        }
    }
    public function changePassword($id)
    {
        $data = [
            'titulo' => 'Registro',
            'menu' => false,
            'data' => $id,
            'subtitle' => 'Cambia tu contraseña de acceso',
        ];
        //$this
    }
}
/** Pruebas
public function metodoVariable()
{
if (func_num_args()>0)
{
for ($i=0; $i<func_num_args();$i++)
{
print func_num_args($i);
}
}
else{print 'No hay argumentos.<br>';}
}
public function metodoFijo($arg1 = "Uno", $arg2 ="Dos", $arg3='Tres')
{
print $arg1.'<br>';
print $arg2.'<br>';
print $arg3.'<br>';
}*/
