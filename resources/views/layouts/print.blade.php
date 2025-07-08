<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link href="https://allfont.es/allfont.css?fonts=dot-matrix" rel="stylesheet" type="text/css" /> --}}
    

    <title>PRINT</title>
    
     
    
     @yield('estilo')
</head>
<body>
  
   
        <div class="content" >
    <!-- Content Header (Page header) -->    
    <!-- /.content-header -->
    <!-- Main content -->      
            @yield('content')   
    
        </div>

    @yield('scri')

    <script type="text/javascript">
        function volver(){
           window.history.back();
        }
        
        function empri(){
          var css = '@page { size: A4 ; margin: 0.5em !important} .tabla{padding: 0.55mm; font-size:9px !important;} .card {margin-bottom:0 !important; margin-top:0; !important} .saltopagina {page-break-before: always; }',
          
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
