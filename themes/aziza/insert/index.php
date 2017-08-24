<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>simple</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

<div class="container">

  <form method="post" id="forem" action="">

    <div class="form-group">
      <label>Type</label>
      <select class="type" name="type">
        <option value="">--</option>
        <option value="keyword">Keyword</option>
        <option value="category">Category</option>
      </select>
    </div>

    <div class="form-group">
      <label>Category</label>
      <select class="category" name="category">
        <option value="">--</option>
      </select>
    </div>

    <div class="form-group subcatz" style="display:none;">
      <label>Subcat</label>
      <span class="subcatx"></span>
    </div>

    <div class="form-group">
      <textarea name="value" rows="8" cols="80"></textarea>
    </div>
    <div class="form-group">
      <span class="btn btn-success" id="hajar">Submit</span>
    </div>

  </form>
  <div class="msg"></div>


</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.16.1/axios.js" charset="utf-8"></script>

<script type="text/javascript">
$(document).ready(function() {

  $(".subcatz").hide();

  list_category();
  sumbit();

  $(".type").change(function() {
    var type = $(".type").val();
    if(type=='keyword'){
      $(".subcatz").fadeIn();
    }else{
      $(".subcatz").fadeOut();
    }
  });

  $(".category").change(function() {
    var type = $(".type").val();
    if(type=='keyword'){
      $(".subcatz").fadeIn();
      list_subcat();
    }else{
      $(".subcatz").fadeOut();
    }
  });

});


function list_subcat(){
  axios.post('insert/action', 'action=get&type=subcat&category='+$(".category").val())
  .then(function (response) {
    var data = response.data;
    $(".subcatx").html('<select class="subcat" name="subcat">');
    for(i=0;i<data.length;i++){
      $(".subcat").append('<option value="'+data[i]._id+'">'+data[i].title+'</option>');
    }
    $(".subcatx").append('</select>');
    console.log(JSON.stringify(data));
    //panggil category
    list_category($(".category").val(),$(".category option:selected").text());
  })
  .catch(function (error) {
    console.log(error);
  });
}

/*
  Insert to DB
*/
function sumbit(){
  $("#hajar").click(
    function(){
      var datane = $("form#forem").serialize();
      axios.post('insert/action', 'action=insert&'+datane)
      .then(function (response) {
        var data = response.data;
        $(".msg").html("");
        for(i=0;i<data.length;i++){
          $(".msg").append('<span class="text-success">'+JSON.stringify(data[i])+'</span><br/>');
        }
        console.log(JSON.stringify(data));
        //panggil category
        list_category($(".category").val(),$(".category option:selected").text());
      })
      .catch(function (error) {
        console.log(error);
      });
    }
  );
}

/*
  Get Kategori
*/
function list_category(a='',b=''){
  $(".category").html("");
  axios.post('insert/action','action=get&type=category')
  .then(function (response) {
    var data = response.data;
    if(a!==''){
      $(".category").html('<option value="'+a+'">'+b+'</option>');
      $(".category").append('<option value="">--</option>');
    }else{
      $(".category").html('<option value="">--</option>');
    }
    for(i=0;i<data.length;i++){
      $(".category").append('<option value="'+data[i]._id+'">'+data[i].title+'</option>');
    }
    console.log(JSON.stringify(data));
  })
  .catch(function (error) {
    console.log(error);
  });
}

</script>















  </body>
</html>
