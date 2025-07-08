<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>CASA DE PRESTAMOS HUANCAYO</title>
  
  <link rel="icon" href="{{ asset('img/sbh.ico') }}" type="image/icon">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <!-- Ionicons -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  
  
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed sidebar-collapse text-sm" >
<!-- Site wrapper -->
<div class="wrapper " id="app" >
<!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div> -->
   <App ruta="{{route('basepath')}}" usuario="{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}" avatar="{{ url(Storage::url( Auth::user()->avatar)) }}"  ></App>      
   
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">
  function volver(){
     window.history.back();
  }
  
  function empri(){
    var css = '@page { size: A4 ; } .saltopagina {page-break-before: always; }',
    head = document.head || document.getElementsByTagName('head')[0],
    style = document.createElement('style');

style.type = 'text/css';
style.media = 'print';

if (style.styleSheet){
  style.styleSheet.cssText = css;
} else {
  style.appendChild(document.createTextNode(css));

  
}

head.appendChild(style);
    window.print();
  }
</script>
</body>
</html>
