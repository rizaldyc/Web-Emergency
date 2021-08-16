<?php

include 'config.php';

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="ol.css" type="text/css">
    <script src="ol.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> 

  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <style>
      .ol-popup {
        position: absolute;
        background-color: white;
        -webkit-filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
        bottom: 12px;
        left: -50px;
        min-width: 280px;
      }
      .ol-popup:after, .ol-popup:before {
        top: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
      }
      .ol-popup:after {
        border-top-color: white;
        border-width: 10px;
        left: 48px;
        margin-left: -10px;
      }
      .ol-popup:before {
        border-top-color: #cccccc;
        border-width: 11px;
        left: 48px;
        margin-left: -11px;
      }
      .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 2px;
        right: 8px;
      }
      .ol-popup-closer:after {
        content: "✖";
      }

    </style>
	<title>Home</title>
</head>
<body>
  
Pilih jenis layer = 
<select onchange="chg_layer(this.value)">
  <option value="bing_aerial">BING Aerial</option>
  <option value="bing_road">BING Road</option>
  <option value="bing_road_demand">BING Road on Demand</option>
  <option value="osm">OSM</option>
  
</select>
<label>
      <input checked type="checkbox" id="chkMall" value="mall" onclick="cek_checkbox()">Mall
  </label>

<div class="row">
    <div id="popup" class="ol-popup">
      <a href="#" id="popup-closer" class="ol-popup-closer"></a>

      <div id="popup-content">
        <div id="foto"></div>
      </div>
      
    </div>
    <div class="col-lg-8" style="background-color:">
      <div id="map" class="map" style="width: 100%; height: 550px;"></div>
      
    </div>
    <div class="col-lg-4" style="background-color: ; padding: 20px">
    
        <h3>Filter Properti</h3>
        <div class="row">
          <div class="col-lg-5">
          Jenis Properti
          </div>
          <div class="col-lg-7">
          <select id="jenis_properti" class="form-control">
          <?php
            $sql = "SELECT * from jenis_properti";
            $result = $conn->query($sql);
            $i=0;
            while($r = $result->fetch_assoc()) {  
          ?>
              <option value="<?php echo $r['idjenisproperti'] ?>"><?php echo $r['nama'] ?></option>
                
          <?php
              $i++;  
              }
          ?>
          </select>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-5">
          Status Properti
          </div>
          <div class="col-lg-7">
          <select id="status_properti" class="form-control">
            <option value=0>Jual</option>
            <option value=1>Sewa</option>
          </select>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-5">
          Min Harga
          </div>
          <div class="col-lg-7">
          <input type="number" id="minharga" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="col-lg-5">
          Maks Harga
          </div>
          <div class="col-lg-7">
          <input type="number" id="maksharga" class="form-control">
          </div>
        </div>
        
        
        <div class="row">
          <div class="col-lg-5">
        
          </div>
          <div class="col-lg-7">
          
          <button type="button" class="btn btn-success" onclick="filter_properti()">
            Filter
          </button>
          </div>
        </div>
    </div>
    
</div>
<a href = "loginpage.php">Settings</a>

    
    	
    
    <script type="text/javascript">
    var koor_x = 112.749942;
    var koor_y = -7.266041;

    var format =  new ol.format.WKT();
    var arr_feature = [];
    var wkt = 'POINT (112.736 -7.276)'; //pemisahnya spasi
    
    

     var titik_testing = format.readFeature(wkt, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      });
     arr_feature[0]= titik_testing;

     wkt = 'POINT (112.778178 -7.211450)'; //pemisahnya spasi


     titik_testing = format.readFeature(wkt, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      });

     arr_feature[1]= titik_testing;

     //style
     var fill_red = new ol.style.Fill({
        color: '#bb3b0e'
      }); 
      var fill_yellow = new ol.style.Fill({
        color: '#f6f578'
      });

      var style_jual= new ol.style.Style({
        fill:fill_red
      });
      var style_sewa= new ol.style.Style({
        fill:fill_yellow
      });
     var style_icon_edu = new ol.style.Style({
        image: new ol.style.Icon({
          anchor: [0.5, 1],
          anchorXUnits: 'fraction',
          anchorYUnits: 'fraction',
          src: 'icons/education.png'
        })
      });
      var style_icon_masjid = new ol.style.Style({
        image: new ol.style.Icon({
          anchor: [0.5, 1],
          anchorXUnits: 'fraction',
          anchorYUnits: 'fraction',
          src: 'icons/default.png'
        })
      });
      var style_icon_gereja = new ol.style.Style({
        image: new ol.style.Icon({
          anchor: [0.5, 1],
          anchorXUnits: 'fraction',
          anchorYUnits: 'fraction',
          src: 'icons/religious.png'
        })
      });
      var style_icon_sekolah = new ol.style.Style({
        image: new ol.style.Icon({
          anchor: [0.5, 1],
          anchorXUnits: 'fraction',
          anchorYUnits: 'fraction',
          src: 'icons/schools.png'
        })
      });
      var style_icon_mall = new ol.style.Style({
        image: new ol.style.Icon({
          anchor: [0.5, 1],
          anchorXUnits: 'fraction',
          anchorYUnits: 'fraction',
          src: 'icons/shopping.png'
        })
      });
      var style_icon_restoran = new ol.style.Style({
        image: new ol.style.Icon({
          anchor: [0.5, 1],
          anchorXUnits: 'fraction',
          anchorYUnits: 'fraction',
          src: 'icons/restaurants.png'
        })
      });
     //endstyle
      
    var format =  new ol.format.WKT();
    var feature;
    var features_tempat=[];
    var arr_feature_masjid = [];
    var arr_feature_gereja = [];
    var arr_feature_sekolah = [];
    var arr_feature_mall = [];
    var arr_feature_restoran = [];

    var masjid=[];
    var gereja=[];
    var sekolah=[];
    var mall=[];
    var restoran=[];
    var features_line=[];
    var features_polygon=[];

    <?php

      $i_masjid = 0;
      $i_gereja = 0;
      $i_sekolah = 0;
      $i_mall = 0;
      $i_restoran = 0;
      $sql = "SELECT * from point_tempat";
      $result = $conn->query($sql);
      $i=0;
      while($r = $result->fetch_assoc()) {  
      ?>
        feature = format.readFeature('<?php echo $r['geom'] ?>', {
              dataProjection: 'EPSG:4326',
              featureProjection: 'EPSG:3857'
            });
        feature.set('tipe','tempat');
        feature.set('id','<?php echo $r['point_id'] ?>');
        feature.set('info','<?php echo $r['keterangan'] ?>');
        
        if(<?php echo $r['tempat_id'] ?> == 1)
        {
          masjid[<?php echo $i_masjid ?>] = feature;
          <?php $i_masjid++; ?>
        }
        else if(<?php echo $r['tempat_id'] ?> == 2)
        {
          gereja[<?php echo $i_gereja ?>] = feature;
          <?php $i_gereja++; ?>
        }
        else if(<?php echo $r['tempat_id'] ?> == 3)
        {
          sekolah[<?php echo $i_sekolah ?>] = feature;
          <?php $i_sekolah++; ?>
        }
        else if(<?php echo $r['tempat_id'] ?>== 4)
        {
          mall[<?php echo $i_mall ?>] = feature;
          <?php $i_mall++; ?>
        }
        else if(<?php echo $r['tempat_id'] ?> == 5)
        {
          restoran[<?php echo $i_restoran ?>] = feature;
          <?php $i_restoran++; ?>
        }
        

             
      <?php
         $i++;  
        }
    ?>
      feature_jual = [];
      feature_sewa = [];
      feature_rumah = [];
      feature_ruko = [];
      feature_gudang = [];
      feature_kantor = [];
      feature_tanah = [];
      <?php
      $i_jual = 0;
      $i_sewa = 0;
      $i_rumah = 0;
      $i_ruko = 0;
      $i_gudang = 0;
      $i_kantor = 0;
      $i_tanah = 0;
      $sql = "SELECT * from properti p 
      inner join jenis_properti jp on p.id_jenisproperti = jp.idjenisproperti";
      $result = $conn->query($sql);
      $i=0;
      while($r = $result->fetch_assoc()) {  
      ?>
        feature = format.readFeature('<?php echo $r['geom'] ?>', {
              dataProjection: 'EPSG:4326',
              featureProjection: 'EPSG:3857'
            });
        feature.set('tipe','properti');
        feature.set('id','<?php echo $r['id_properti'] ?>');
        
        
        if(<?php echo $r['status_properti'] ?> == '0')
        {
          feature.set('info','<?php echo "Jual ".$r['nama'] ?>');
          feature_jual[<?php echo $i_jual ?>] = feature;
          <?php $i_jual++; ?>
        }
        else if(<?php echo $r['status_properti'] ?> == '1')
        {
          feature.set('info','<?php echo "Sewa ".$r['nama'] ?>');
          feature_sewa[<?php echo $i_sewa ?>] = feature;
          <?php $i_sewa++; ?>
        }

        if(<?php echo $r['id_jenisproperti'] ?> == '1')
        {
          
          feature_rumah[<?php echo $i_rumah ?>] = feature;
          <?php $i_rumah++; ?>
        }
        else if(<?php echo $r['id_jenisproperti'] ?> == '2')
        {
          feature_ruko[<?php echo $i_ruko ?>] = feature;
          <?php $i_ruko++; ?>
        }
        else if(<?php echo $r['id_jenisproperti'] ?> == '3')
        {
          feature_gudang[<?php echo $i_gudang ?>] = feature;
          <?php $i_gudang++; ?>
        }
        else if(<?php echo $r['id_jenisproperti'] ?> == '4')
        {
          feature_kantor[<?php echo $i_kantor ?>] = feature;
          <?php $i_kantor++; ?>
        }
        else if(<?php echo $r['id_jenisproperti'] ?> == '5')
        {
          feature_tanah[<?php echo $i_tanah ?>] = feature;
          <?php $i_tanah++; ?>
        }

       
      <?php
         $i++;  
        }
    ?>


      

        //pop up
      var container = document.getElementById('popup');
      var content = document.getElementById('popup-content');
      var closer = document.getElementById('popup-closer');
      var overlay = new ol.Overlay( ({
        element: container,
        autoPan: true,
        autoPanAnimation: {
          duration: 250
        }
      }));
       closer.onclick = function() {
        overlay.setPosition(undefined);
        closer.blur();
        return false;
      };
    //end pop up
        //layer
        var vector_jual = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: feature_jual
            })
            , style : style_jual
          });
          var vector_sewa = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: feature_sewa
            })
            , style : style_sewa
          });
        var contoh_features_point=[];
        var vector = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: features_tempat
            })
            , style : style_icon_edu
        });
        var vector_masjid = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: masjid
            })
            , style : style_icon_masjid
        });
        var vector_gereja = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: gereja
            })
            , style : style_icon_gereja
        });
        var vector_sekolah = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: sekolah
            })
            , style : style_icon_sekolah
        });
        var vector_mall = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: mall
            })
            , style : style_icon_mall
        });
        var vector_restoran = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: restoran
            })
            , style : style_icon_restoran
        });
        

        var vector_line = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: features_line
            })
          });
        var vector_polygon = new ol.layer.Vector({
            source: new  ol.source.Vector({
              features: features_polygon
            })
          });
        var source_tempat=new  ol.source.Vector({
          features: features_tempat
        });
        var source_masjid=new  ol.source.Vector({
          features: masjid
        });
        var source_gereja=new  ol.source.Vector({
          features: gereja
        });
        var source_sekolah=new  ol.source.Vector({
          features: sekolah
        });
        var source_mall=new  ol.source.Vector({
          features: mall
        });

        var tempat = new ol.layer.Vector({
          source: source_tempat
        });

        var layer_osm = new ol.layer.Tile({
                source: new ol.source.OSM()
              });
        var layer_bing_aerial = new ol.layer.Tile({
                source: new ol.source.BingMaps({
                key: 'Assg_L_LFwigqPz8-M6v1PKqzG8QysOjkLK6q5_p0miYRm80v0Qr70cZ8516vAEx',
                imagerySet: 'AerialWithLabels'})
              });
        var layer_bing_road = new ol.layer.Tile({
                source: new ol.source.BingMaps({
                key: 'Assg_L_LFwigqPz8-M6v1PKqzG8QysOjkLK6q5_p0miYRm80v0Qr70cZ8516vAEx',
                imagerySet: 'Road'})
              });
        var layer_bing_road_demand = new ol.layer.Tile({
                source: new ol.source.BingMaps({
                key: 'Assg_L_LFwigqPz8-M6v1PKqzG8QysOjkLK6q5_p0miYRm80v0Qr70cZ8516vAEx',
                imagerySet: 'RoadOnDemand'})
              });
    //endlayer

    
    var map = new ol.Map({
        target: 'map',
        layers: [
          layer_osm,
          layer_bing_road,
          layer_bing_road_demand,
          layer_bing_aerial,
          vector,
          vector_line,
          vector_polygon,
          tempat,
          vector_masjid,
          vector_gereja,
          vector_sekolah,
          vector_mall,
          vector_restoran,
          vector_jual,
          vector_sewa


        ],
        controls: [
          //Define the default controls
          new ol.control.Zoom(),
          new ol.control.Rotate(),
          new ol.control.Attribution(),
          //Define some new controls
          new ol.control.ZoomSlider(),
          new ol.control.MousePosition(),
          new ol.control.ScaleLine(),
          new ol.control.OverviewMap()
          ],

        view: new ol.View({
          center: ol.proj.fromLonLat([koor_x,koor_y]),
          zoom: 12
        })
    });
    map.addOverlay(overlay);
       map.on('singleclick', function(evt) {
        var coordinate = evt.coordinate;
        infoPoint(evt.pixel);
        overlay.setPosition(coordinate);
      });

    //function
      function chg_layer(param_val)
      {
        //alert(param_val);
        layer_osm.setVisible(false);
        layer_bing_aerial.setVisible(false);
        layer_bing_road.setVisible(false);
        layer_bing_road_demand.setVisible(false);
        


        if(param_val == 'osm'){
          layer_osm.setVisible(true);
        }
        else if(param_val == 'bing_collins'){
          layer_bing_collins.setVisible(true);
        }
        else if(param_val == 'bing_road'){
          layer_bing_road.setVisible(true);
        }
        else if(param_val == 'bing_road_demand'){
          layer_bing_road_demand.setVisible(true);
        }
        else if(param_val == 'bing_aerial'){
          layer_bing_aerial.setVisible(true)
        }


      }
      function digit_tempat(){
        var draw = new ol.interaction.Draw({
            source: source_tempat,
            type: 'Point'
          });
          map.addInteraction(draw);
            draw.on('drawend', function(evt){
              source_tempat.refresh({force:true}); 
              var feature = evt.feature;
              var geom = feature.getGeometry().clone();
              geom=geom.transform('EPSG:3857','EPSG:4326');
              var wkt  = format.writeGeometry(geom);
              $('#wkt_point').val(wkt);
            }); 

      }

      function simpan_tempat(){
        var keterangan=$('#keterangan_tempat').val();
        var wkt=$('#wkt_point').val();
        var jenis = $('#jenis_tempat').val();

        var url ="simpan_tempat.php?keterangan="+keterangan+'&wkt='+wkt+'&jenis='+jenis;
          $.ajax({
            url: url,
            success: function(data){
              alert(data);
              console.log(data); 
              if(data == 'data berhasil tersimpan'){
                location.reload();
              }
                
            }
        });

      }
      function digit_properti(){
        var draw = new ol.interaction.Draw({
            source: source_tempat,
            type: 'Polygon'
          });
          map.addInteraction(draw);
            draw.on('drawend', function(evt){
              source_tempat.refresh({force:true}); 
              var feature = evt.feature;
              var geom = feature.getGeometry().clone();
              geom=geom.transform('EPSG:3857','EPSG:4326');
              var wkt  = format.writeGeometry(geom);
              $('#wkt_polygon').val(wkt);
            }); 

      }
      function simpan_properti(){
        var jenis = $('#jenis_properti').val();
        var status = $('#status_properti').val();
        
        var harga = $('#harga').val();
        var luastanah = $('#luas_tanah').val();
        var luasbangunan = $('#luas_bangunan').val();
        var alamat = $('#alamat').val();
        var keterangan_properti = $('#keterangan_properti').val();
        var wkt = $('#wkt_polygon').val();
        var raw_link = $('#link_foto').val();
        //var link_foto = raw_link.split(" ");
        

        var url ="simpan_properti.php?jenis="+jenis+'&wkt='
        +wkt+'&status='+status+'&harga='+harga+'&luastanah='
        +luastanah+'&luasbangunan='+luasbangunan+'&alamat='+alamat+'&link_foto='+raw_link
        +'&keterangan_properti='+keterangan_properti;
          $.ajax({
            url: url,
            success: function(data){
              alert(data);
              console.log(data); 
              if(data == 'data berhasil tersimpan'){
                location.reload();
              }
                
            }
        });

      }
      function hapus_tempat(id){
        var url ="hapus_tempat.php?id="+id;
          $.ajax({
            url: url,
            success: function(data){
              alert(data);
              console.log(data); 
              if(data == 'data berhasil terhapus'){
                location.reload();
              }
                
            }
        });

      }
      function hapus_properti(id){
        var url ="hapus_properti.php?id="+id;
          $.ajax({
            url: url,
            success: function(data){
              alert(data);
              console.log(data); 
              if(data == 'data berhasil terhapus'){
                location.reload();
              }
                
            }
        });

      }
     
      var infoPoint = function(pixel) {
       var feature = map.forEachFeatureAtPixel(pixel, function(feature) {
          return feature;
        });
        if (feature) {
          if(feature.get('info')){
            if(feature.get('tipe') == 'tempat'){
            content.innerHTML =  feature.get('info');
            }
            if(feature.get('tipe') == 'properti'){
            content.innerHTML =  feature.get('info')+
            '<br><a href="detail_properti_client.php?id='+feature.get('id')+'"target="_blank">Selengkapnya</a>';
            }
            
          }
          else{
            content.innerHTML = 'tidak ada point terpilih';
          }
          
        } else {
          content.innerHTML = 'tidak ada point terpilih';
        }
      };

      function cek_checkbox()
      {
        var mall = document.getElementById("chkMall");
        vector_mall.setVisible(false);
        
        if(mall.checked == true)
        {
          vector_mall.setVisible(true);
        }
        

      }
      function filter_properti(){
        var jenis = $('#jenis_properti').val();
        var status = $('#status_properti').val();
        var minharga = $('#minharga').val();
        var maksharga = $('#maksharga').val();
        var url = "filter_properti.php?jenispro="+jenis+'&statuspro='+status+'&minhargapro='+minharga+'&makshargapro='+maksharga;
        window.open(url,"_blank");
      }

    //endfunction
    </script>

</div>
</body>
</html>