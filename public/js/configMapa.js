$(document).ready(function(e){
  var regiones = {"d200":"1","d26":"2","d2" : "3","d3" : "3"}
  var projects = {"3" : "Concejo: ", "2" : "Concejo: "}
  $('#world-map').vectorMap({
    map: 'peru',
    onRegionOver: function (e, code) {
      $(this).on('click', function () {
        var map = $('#world-map').vectorMap('get', 'mapObject');
        var name = map.getRegionName(code);
        var objregion = map.regions[code];
        // alert(code, name, objregion.config['id']); 
      });
    },
    onRegionOut: function (e, code) {
      $(this).unbind('click');
    },
    zoomButtons : false,
    zoomOnScroll : false,
    backgroundColor: '#b3d1ff',
    series: {
    regions: [{
      values: regiones,
      scale: ['#E0E0E0', '#b3d1ff',"#1A3984"],
      normalizeFunction: 'polynomial',
    }]
    },
    regionStyle:{
      initial:{
        fill:"#f4f3f0",
        stroke:"#666666",
        "stroke-width":1,
        "stroke-opacity":1
      },
      hover:{
        // "fill":"#1B2B34",
        // "fill-opacity": 0.1,
        cursor: 'pointer'
      }
    },
    regionLabelStyle:{
      initial: {
      'font-family': 'Verdana',
      'font-size': '18',
      'font-weight': 'bold',
      cursor: 'default',
      fill: 'black'
      },
      hover: {
        cursor: 'pointer'
      }
    },
    onMarkerLabelShow: function(event, label, code) {
     label.html("<img src=\"img/logo.png\"><br>"+ label.html());                
    },
    onRegionLabelShow: function(event, label, code){
      var map = $('#world-map').vectorMap('get', 'mapObject');
      var name = map.getRegionName(code);
      var name = map.getRegionName(code);
      var objregion = map.regions[code];
      var data = objregion.config;
      if(typeof(projects[data.id]) != "undefined"){
        var proyecto = projects[data.id];
      } else {
        var proyecto = "";
      }
      label.html('<strong>'+name+'</strong><br>'+'<p>'+proyecto+'</p>');
      // label.html(names[currentLang][code]);
    }  

  })

});