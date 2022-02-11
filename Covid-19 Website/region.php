<?php
  class Region{
      public $name;
      public $alertLevel;
      public $alertDate;
    }

  function getRegionsFromName($name){
    $query = 'SELECT * FROM Region WHERE Region.regionName = "'.$name.'"';
            
    return getRegionList($query);
  }
  
  function getRegionsFromCity($city){
    $query = 'SELECT * FROM Region, RegionCity WHERE "'.$city.'" = RegionCity.cityName AND Region.regionName = RegionCity.regionName';
            
    return getRegionList($query);
  }
  
  function getRegionsFromPostal($postal){
    $query = 'SELECT * FROM Region, RegionCity, CityPostal WHERE CityPostal.cityName = RegionCity.cityName AND Region.regionName = RegionCity.regionName AND CityPostal.postalCode = \''.$postal.'\'';
    
    return getRegionList($query);
  }
  
  function getRegionList($query){
    $result = executeQuery($query);
    
    $regionList = array();
    while($row = $result->fetch_assoc()){
      $region = new Region();
      
      $region -> name = $row['regionName'];
      $region -> alertLevel = $row['alertLevel'];
      $region -> alertDate = $row['alertDate'];
      
      array_push($regionList, $region);
    }
    
    usort($regionList, function($a, $b) {
        return strtotime($b -> alertDate) - strtotime($a -> alertDate);
    });

    return $regionList;
  }
?>