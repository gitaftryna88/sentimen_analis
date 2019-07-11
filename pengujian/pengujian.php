<?php
$pro="simpan";
$tgl=WKT(date("Y-m-d"));
$jam=date("H:i:s");
?>
<link type="text/css" href="<?php echo "$PATH/base/";?>ui.all.css" rel="stylesheet" />   
<script type="text/javascript" src="<?php echo "$PATH/";?>jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo "$PATH/";?>ui/ui.core.js"></script>
<script type="text/javascript" src="<?php echo "$PATH/";?>ui/ui.datepicker.js"></script>
<script type="text/javascript" src="<?php echo "$PATH/";?>ui/i18n/ui.datepicker-id.js"></script>

  <script type="text/javascript"> 
      $(document).ready(function(){
        $("#tgl").datepicker({
          dateFormat  : "dd MM yy",        
          changeMonth : true,
          changeYear  : true          
        });
      });
    </script>    

<script type="text/javascript"> 
function PRINT(){ 
win=window.open('pengujian/print.php','win','width=1000, height=400, menubar=0, scrollbars=1, resizable=0, location=0, toolbar=0, penilaian=0'); } 
</script>
<script language="JavaScript">
function buka(url) {window.open(url, 'window_baru', 'width=800,height=600,left=320,top=100,resizable=1,scrollbars=1');}
</script>

<link rel="stylesheet" href="js/jquery-ui.css">
  <link rel="stylesheet" href="resources/demos/style.css">
<script src="js/jquery-1.12.4.js"></script>
  <script src="js/jquery-ui.js"></script>
  
  


<?php
if($_GET["pro"]=="ubah"){
  $id_pengujian=$_GET["kode"];
  $sql="select * from `$tbpengujian` where `id_pengujian`='$id_pengujian'";
  $d=getField($conn,$sql);
        $id_pengujian=$d["id_pengujian"];
        $tgl=$d["tgl"];
        $jam=$d["jam"];
        $komentar=$d["komentar"];
        $penilaian=$d["penilaian"];
        $keterangan=$d["keterangan"];
        $pro="ubah";    
}
?>


<div id="accordion">
  <h4>Input Data Pengujian</h4>
  <div>
<form action="" method="post" enctype="multipart/form-data">
<table width="517">

<br><br>

        
        <tr>
          <td height="51"><label for="komentar">Komentar</label>
          <td>:
          <td><textarea name="komentar" cols="30" rows="3" class="form-control" id="komentar"><?php echo $komentar;?></textarea>
          <label for="id_pengujian"></label></td>
        </tr>

        <tr>
          <td height="26">
          <td>
          <td colspan="2">  <input name="Simpan" type="submit" id="Simpan" value="Simpan" />
            <input name="pro" type="hidden" id="pro" value="<?php echo $pro;?>" />
       
            <a href="?mnu=pengujian"><input name="Batal" type="button" id="Batal" value="Batal" /></a>
        </td></tr>
      </table>
      </form>

<br><br><br><br><br>



<?php

if(isset($_POST["Simpan"])){
  $kalimat=strip_tags($_POST["komentar"]);
  $pesan=$kalimat;
  
 require_once __DIR__ . '../../vendor/autoload.php';
 $initos = new \Sastrawi\Stemmer\StemmerFactory();
 $bikinos = $initos->createStemmer();

$kalimat=strtolower($kalimat); 
$stemming=$bikinos->stem($kalimat);
$stemmingnew=strtolower($stemming);
 
$ak=getStopNumber();
$ar=getStopWords();
$wordStop=$stemmingnew;
for($i=0;$i<count($ar);$i++){
 $wordStop =str_replace($ar[$i]." ","", $wordStop); 
}

for($i=0;$i<count($ak);$i++){
 $wordStop =str_replace($ak[$i],"", $wordStop); 
}
$stopword=str_replace("  "," ", $wordStop); 

  
  //============================================
  $i=0;
  $tot=0;
  $sqlq="select distinct(kategori) from `$tbkategori` order by `kategori` asc";
  $arrq=getData($conn,$sqlq);
    foreach($arrq as $dq) {             
        $kategori=$dq["kategori"];
        $nk=$dq["kategori"];
  
   $sql="select kategori from `$tbkategori` where kategori='$kategori' order by `kategori` asc";
  $jum=getJum($conn,$sql);
  
  $arKat[$i]=$kategori;
  $arIdKat[$i]=$kategori;
  $arJum[$i]=$jum;

  $tot+=$jum;
  $i++;
  }//foreach
  $p=$i;
  
 // echo"<table border='1' width='60%'>";
 // echo"<tr bgcolor='#bbbbbb'><td align='center'>No<td align='center'>Kategori<td>Jumlah</tr>";
  for($i=0;$i<$p;$i++){
    $no=$i+1;
    $kat=$arKat[$i];
    $jum=$arJum[$i];
    $color='#dddddd';
    if($i%2==0){$color='#eeeeee';}
 // echo"<tr bgcolor='$color'><td>$no<td>$kat<td align='right'>$jum</tr>";  
  }//for
 // echo"</table>";
 // echo"Total data=".$tot."<br>";
  
  
  $gab="";
  $sql="select stemming from `$tbkategori` order by `kategori` asc";
  $arr=getData($conn,$sql);
    foreach($arr as $d) {             
        $normalisasi=$d["stemming"];
        if(strlen($normalisasi)>0){
          $gab.=$normalisasi." ";
        }
    }
$ar0=explode(" ",$gab);

$ar=getUnik($ar0);
$N=count($ar);  
  
//  echo"<strong Tokenization</strong>";
  $no=0;
 // echo"<table border='1' width='60%'>";
 // echo"<tr bgcolor='#bbbbbb'><td align='center'>No<td align='center'>Token";
    for($i=0;$i<$p;$i++){
    $kat=$arKat[$i];
 //   echo"<td>".$kat;
    }
    echo"</tr>";
      for($j=0;$j<count($ar)-1;$j++){
        $no=$j+1;
        $color='#dddddd';
        if($no%2==0){$color='#eeeeee';}
        
        $KAL=$ar[$j];
        
    //  echo"<tr bgcolor='$color'><td>$no<td>$KAL";
         for($i=0;$i<$p;$i++){
          $idk=$arIdKat[$i];
          $kalimat=$KAL;
          
          $r=getHitung($conn,$idk,$kalimat);//rand(0,1);
     //     echo"<td>".$r;
          }
     //     echo"</tr>";
      }//for
 // echo"</table>";


  $ark=explode(" ",$stopword);
  
 //echo"<table border='1' width='60%'>";
// echo"<tr bgcolor='#bbbbbb'><td align='center'>No<td align='center'>Kategori</td>";
        for($j=0;$j<count($ark);$j++){        
   //     echo"<td>".$ark[$j];
      }
//echo"</tr>";
      
  $pan=$p;
  for($i=0;$i<$pan;$i++){
    $no=$i+1;
    $idk=$arIdKat[$i];
    $kat=$arKat[$i];
    $jum=$arJum[$i];
    $color='#dddddd';
    if($i%2==0){$color='#eeeeee';}
    
    $n=$tot;
    $p=$jum/$tot;
    $m=count($ark);
    
 //     echo"<tr bgcolor='$color'><td>$no<td>$kat</td>";
    $totc=$p;
    $stotc="$p x ";
    
        for($j=0;$j<$m;$j++){
          $kata=$ark[$j];       
            $nc=getHitung($conn,$idk,$kata);
          
          $ajum[$i][$j]=$nc;
          $bob[$i][$j]=($nc+($m * $p))/($n+$m);
          $totc *=$bob[$i][$j];
          $stotc .=$bob[$i][$j]." x ";
          
   //     echo"<td>"."($nc+($m * $p))/($n+$m)<br>=".$bob[$i][$j];
      }
      $arTotc[$i]=$totc;
      $arSTotc[$i]=$stotc;
      
   //   echo"</tr>";
    
  }//for
 // echo"</table><br>";
  
//  echo"Perhitungan Probabilitas";
// echo"<table border='1' width='100%'>";
// echo"<tr bgcolor='#bbbbbb'><td align='center'>No<td align='center'>Kategori</td><td width='60%'>Formulas<td>Total</tr>";
  for($i=0;$i<$pan;$i++){
    $no=$i+1;
    $kat=$arKat[$i];
    $color='#dddddd';
    if($i%2==0){$color='#eeeeee';}
    
    $no=$i+1;
   
  }
  
  
  //bubblerost
        for($x = 0; $x < $pan; $x++){
            for($a = 0 ;  $a < $pan - 1 ; $a++){
                if($a < $pan ){
                    if($arTotc[$a] < $arTotc[$a + 1] ){
                            swap($arTotc, $a, $a+1);
               swap($arSTotc, $a, $a+1);
                swap($arKat, $a, $a+1);
                    }
                }
            }
        }
    
  
  
 $hs="";
  for($i=0;$i<$pan;$i++){
    $no=$i+1;
    $kat=$arKat[$i];
    $color='#dddddd';
    if($i%2==0){$color='#eeeeee';}
    if($i==0){$hs=$kat;}
    $no=$i+1;
     }


//$sql="UPDATE `$tbpengujian` set penilaian='$hs' where `id_pengujian`='$id_pengujian'";
//$up=process($conn,$sql);





  $id_pengujian=strip_tags($_POST["id_pengujian"]);
  $tgl=date("Y-m-d");
  $jam=date("H:i:s");;
  
  
  
  $keterangan="-";
  
 $sql=" INSERT INTO `$tbpengujian` (
`id_pengujian` ,
`tgl` ,
`jam` ,
`komentar` ,
`penilaian` ,
`keterangan` 
) VALUES (
'', 
'$tgl', 
'$jam',
'$pesan',
'$hs',
'$stemmingnew'
)";
  
$simpan=process($conn,$sql);



//============================
echo"<font color='green' size='24'>Kategori: $hs</font>";
}//isset Proses
?>


</div> 
 <h4>Data Pengujian </h4>
 <div>
</blockquote>


Data pengujian :
| <a href="pengujian/pdf.php"><img src='ypathicon/pdf.png' alt='PDF'></a>
| <img src='ypathicon/print.png' alt='PRINT' OnClick="PRINT()"> |
<br>
<table class="table table-bordered table-striped">
<thead>
  <tr>
    <th width="3%"><center>No</th>
    <th width="87%"><center>Komentar</th>
    <th width="10%"><center>Menu</th>
  </tr>
  </thead>
  <tbody>
<?php  
  $sql="select * from `$tbpengujian` order by `id_pengujian` desc";
  $jum=getJum($conn,$sql);
    if($jum > 0){
  //--------------------------------------------------------------------------------------------
  $batas   = 30;
  $page = $_GET['page'];
  if(empty($page)){$posawal  = 0;$page = 1;}
  else{$posawal = ($page-1) * $batas;}
  
  $sql2 = $sql." LIMIT $posawal,$batas";
  $no = $posawal+1;
  //--------------------------------------------------------------------------------------------                  
  $arr=getData($conn,$sql2);
    foreach($arr as $d) {             
        $id_pengujian=$d["id_pengujian"];
        $tgl=WKT($d["tgl"]);
        $jam=$d["jam"];
        $komentar=$d["komentar"];
        $penilaian=$d["penilaian"];
        $keterangan=$d["keterangan"];


          
echo"<tr>
        <td>$no</td>
        <td valign='top'>Komentar: <b>$komentar</b>
        <br>Stemming: <i>$keterangan</i>
        <br>Kategori Penilaian: <b>$penilaian</b>    #$tgl $jam wib</td>
        <td align='center'>
<a href='?mnu=tfidf&id=$id_pengujian'><img src='ypathicon/xls.png' title='Proses TF-IDF'></a>
<a href='?mnu=pengujian&pro=hapus&kode=$id_pengujian'><img src='ypathicon/h.png' alt='hapus' 
onClick='return confirm(\"Apakah Anda benar-benar akan menghapus $nama pada data pengujian ?..\")'></a></td>
        </tr>";
      
      $no++;
      }//while
    }//if
    else{echo"<tr><td colspan='7'><blink>Maaf, Data pengujian belum tersedia...</blink></td></tr>";}
?>
</tbody>
</table>

<?php
//Langkah 3: Hitung total data dan page 
$jmldata = $jum;
if($jmldata>0){
  if($batas<1){$batas=1;}
  $jmlhal  = ceil($jmldata/$batas);
  echo "<div class=paging>";
  if($page > 1){
    $prev=$page-1;
    echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=$prev&mnu=pengujian'>« Prev</a></span> ";
  }
  else{echo "<span class=disabled>« Prev</span> ";}

  // Tampilkan link page 1,2,3 ...
  for($i=1;$i<=$jmlhal;$i++)
  if ($i != $page){echo "<a href='$_SERVER[PHP_SELF]?page=$i&mnu=pengujian'>$i</a> ";}
  else{echo " <span class=current>$i</span> ";}

  // Link kepage berikutnya (Next)
  if($page < $jmlhal){
    $next=$page+1;
    echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=$next&mnu=pengujian'>Next »</a></span>";
  }
  else{ echo "<span class=disabled>Next »</span>";}
  echo "</div>";
  }//if jmldata

$jmldata = $jum;
  echo "<p align=center>Total Data <b>$jmldata</b> Item</p>";
?>
</div>

</div>


<?php
if(isset($_POST["SimpanX"])){
  $pro=strip_tags($_POST["pro"]);
  $id_pengujian=strip_tags($_POST["id_pengujian"]);
  $tgl=date("Y-m-d");
  $jam=date("H:i:s");;
  $komentar=strip_tags($_POST["komentar"]);
  
  
  
  $penilaian="";
  $keterangan="-";
  
if($pro=="simpan"){
$sql=" INSERT INTO `$tbpengujian` (
`id_pengujian` ,
`tgl` ,
`jam` ,
`komentar` ,
`penilaian` ,
`keterangan` 
) VALUES (
'$id_pengujian', 
'$tgl', 
'$jam',
'$komentar',
'$penilaian',
'$keterangan'
)";
  
$simpan=process($conn,$sql);


if($simpan) {echo "<script>alert('Data $id_pengujian berhasil disimpan !');document.location.href='?mnu=pengujian';</script>";}
    else{echo"<script>alert('Data $id_pengujian gagal disimpan...');document.location.href='?mnu=pengujian';</script>";}
  }
  else{
$sql="update `$tbpengujian` set 
`tgl`='$tgl',
`jam`='$jam' ,
`komentar`='$komentar',
`penilaian`='$penilaian',
`keterangan`='$keterangan' 
where `id_pengujian`='$id_pengujian'";
$ubah=process($conn,$sql);
  if($ubah) {echo "<script>alert('Data $id_pengujian berhasil diubah !');document.location.href='?mnu=pengujian';</script>";}
  else{echo"<script>alert('Data $id_pengujian gagal diubah...');document.location.href='?mnu=pengujian';</script>";}
  }//else simpan
}
?>

<?php
if($_GET["pro"]=="hapus"){
$id_pengujian=$_GET["kode"];
$sql="delete from `$tbpengujian` where `id_pengujian`='$id_pengujian'";
$hapus=process($conn,$sql);
if($hapus) {echo "<script>alert('Data pengujian $id_pengujian berhasil dihapus !');document.location.href='?mnu=pengujian';</script>";}
else{echo"<script>alert('Data pengujian $id_pengujian gagal dihapus...');document.location.href='?mnu=pengujian';</script>";} }
?>





<?php


    function swap2(&$arr, $a, $b) {
        $tmp = $arr[$a];
        $arr[$a] = $arr[$b];
        $arr[$b] = $tmp;
    }
  
  

function getUnik($a){
  $b=array_unique($a);
  $i=0;
    for($m=0;$m<=count($a);$m++){
      if($b[$m]==""){}
      else{
      $c[$i]=$b[$m];
      $i++;
      }
    }
return $c;    
} 
function getHitung($conn,$idk,$kata){
$sql="select kategori from `tb_kategori` where `kategori`='$idk' and `stemming` like '%$kata%'";//kalimat
  $rs=$conn->query($sql);
  $jum= $rs->num_rows;
  $rs->free();
  return $jum;
}

  ?>
