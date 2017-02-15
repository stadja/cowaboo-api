<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="x-ua-compatible" content="IE=edge">
  <title>CoWaBoo API</title>
  <link rel="icon" type="image/png" href="{{asset('favicon.ico')}}" />
  <link href='{{asset('vendor/cowaboo_api/swagger-ui/css/typography.css')}}'  media='screen' rel='stylesheet' type='text/css'/>
  <link href='{{asset('vendor/cowaboo_api/swagger-ui/css/reset.css')}}'  media='screen' rel='stylesheet' type='text/css'/>
  <link href='{{asset('vendor/cowaboo_api/swagger-ui/css/screen.css')}}'  media='screen' rel='stylesheet' type='text/css'/>
  <link href='{{asset('vendor/cowaboo_api/swagger-ui/css/reset.css')}}'  media='print' rel='stylesheet' type='text/css'/>
  <link href='{{asset('vendor/cowaboo_api/swagger-ui/css/print.css')}}'  media='print' rel='stylesheet' type='text/css'/>

  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/object-assign-pollyfill.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/jquery-1.8.0.min.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/jquery.slideto.min.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/jquery.wiggle.min.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/jquery.ba-bbq.min.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/handlebars-4.0.5.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/lodash.min.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/backbone-min.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/swagger-ui.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/highlight.9.1.0.pack.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/highlight.9.1.0.pack_extended.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/jsoneditor.min.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/marked.js')}}' type='text/javascript'></script>
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lib/swagger-oauth.js')}}' type='text/javascript'></script>

  <!-- Some basic translations -->
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lang/translator.js')}}' type='text/javascript'></script>
  {{-- <script src='{{asset('vendor/cowaboo_api/swagger-ui/lang/en.js')}}' type='text/javascript'></script> --}}
  <script src='{{asset('vendor/cowaboo_api/swagger-ui/lang/fr.js')}}' type='text/javascript'></script>

  <script type="text/javascript">
    $(function () {
      var url = window.location.search.match(/url=([^&]+)/);
      if (url && url.length > 1) {
        url = decodeURIComponent(url[1]);
      } else {
        url = "{{route('api.json')}}";
      }

      hljs.configure({
        highlightSizeThreshold: 5000
      });

      // Pre load translate...
      if(window.SwaggerTranslator) {
        window.SwaggerTranslator.translate();
      }
      window.swaggerUi = new SwaggerUi({
        url: url,
        dom_id: "swagger-ui-container",
        supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
        onComplete: function(swaggerApi, swaggerUi){
          if(typeof initOAuth == "function") {
            initOAuth({
              clientId: "your-client-id",
              clientSecret: "your-client-secret-if-required",
              realm: "your-realms",
              appName: "your-app-name",
              scopeSeparator: " ",
              additionalQueryStringParams: {}
            });
          }

          if(window.SwaggerTranslator) {
            window.SwaggerTranslator.translate();
          }
        },
        onFailure: function(data) {
          log("Unable to Load SwaggerUI");
        },
        docExpansion: "none",
        jsonEditor: false,
        defaultModelRendering: 'schema',
        showRequestHeaders: false,
        showOperationIds: false
      });

      window.swaggerUi.load();

      function log() {
        if ('console' in window) {
          console.log.apply(console, arguments);
        }
      }
  });
  </script>
</head>

<body class="swagger-section">
{{-- <div id='header'>
  <div class="swagger-ui-wrap">
    <a id="logo" href="http://swagger.io"><img class="logo__img" alt="swagger" height="30" width="30" src="{{asset('vendor/cowaboo_api/swagger-ui/images/logo_small.png')}}" /><span class="logo__title">swagger</span></a>
  </div>
</div> --}}

<div id="message-bar" class="swagger-ui-wrap" data-sw-translate>&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
