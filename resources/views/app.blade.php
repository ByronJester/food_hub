<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.15.0/maps/maps.css" rel="stylesheet" type="text/css">
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.15.0/maps/maps-web.min.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/4865c75cef.js" crossorigin="anonymous"></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <style>
      body {
        overflow-x:hidden;
        overflow-y:scroll;
      }

      body::-webkit-scrollbar {
        width: 0px;
        background: white;
      }

      body::-webkit-scrollbar-thumb {
        background: #ffffff;
        height:30px;
      }

      body::-webkit-scrollbar-track-piece{
        display:none;
      }
    </style>
  </head>
  <body>
    @inertia
  </body>
</html>