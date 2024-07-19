<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="insert-update.css">
  <style>
    .container{
      margin-top: 80px;
    }
    th{
      height: 30px;
      text-align: center;
    }
    td{
      /* border: solid 1px; */
      height: 100px;
    }
    .today{
      background: aqua;
      font-size: 20px;
      color: black;
    }

    .date{
      font-size: 20px;
      color: black;
    }
    .set_value{
      width: 68px;
    }
    .set_stock{
      width: 40px;
    }



.off label,
.on label,
.off2 label,
.on2 label {
  box-sizing: border-box;
  /* text-align: center; */
  border: 1px solid #ccc;
  /* border-radius: 3px; */
  height: 20px;
  width: 40px;
  line-height: 20px;
  font-weight: normal;  
  background: #fff;
  box-shadow: 2px 2px 6px #888;  
  transition: .3s;
  border-radius: 4px;
  }

  .off input[type="checkbox"],
  .on input[type="checkbox"],
  .off2 input[type="checkbox"],
  .on2 input[type="checkbox"] {
  display : none;
  }

  <?php foreach($id_css as $ids){
  echo '.id'.$ids.'{color: red;}';
  echo '.off'.' #id'.$ids.':checked + label span:after'.'{content: "売止"; color: red;}';
  echo '.off'.' #id'.$ids.' + label span:after'.'{content: "販売"; color: #333;}';
  echo '.on'.' #id'.$ids.' + label span:after'.'{content: "売止"; color: red;}';
  echo '.on'.' #id'.$ids.':checked + label span:after'.'{content: "販売"; color: #333;}';
}?>

<?php foreach($id_css2 as $ids2){
  echo '.id'.$ids2.'{color: red;}';
  echo '.off2'.' #id2'.$ids2.':checked + label span:after'.'{content: "売止"; color: red;}';
  echo '.off2'.' #id2'.$ids2.' + label span:after'.'{content: "販売"; color: #333;}';
  echo '.on2'.' #id2'.$ids2.' + label span:after'.'{content: "売止"; color: red;}';
  echo '.on2'.' #id2'.$ids2.':checked + label span:after'.'{content: "販売"; color: #333;}';
  }?>




  </style>