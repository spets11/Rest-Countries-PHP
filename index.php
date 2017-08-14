<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>REST Countries</title>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript">
    function addCommas(nStr)
    {
	    nStr += '';
	    x = nStr.split('.');
	    x1 = x[0];
	    x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
		    x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    return x1 + x2;
    }
    $(document).ready(function() {
	$("#submit_btn").click(function() { 
		output_error = ""
		output = ""
		count = 0
		$("#search_form #search_results table tbody").html('');
		$("#search_form #search_error").html('');
		post_data = {'name' : $('input[name=name]').val(), 'search_type' : $('select[name=search_type]').val()};
		//Ajax post data to server
		$.post('results.php', post_data, function(response){  
			if(response.type == 'error'){ //load json data from server and output message     
			    output_error = '<div class="error">'+response.text+'</div>';
			}else{
			    $.each(response.text, function(index, item) {
				output += "<tr style='vertical-align: top;'><td>"+item.name+"</td>"
				output += "<td>"+item.alpha2Code+"</td>"
				output += "<td>"+item.alpha3Code+"</td>"
				output += "<td><div style='height:30px;width:80px'><img src='"+item.flag+"' style='height: 100%; width: 100%; object-fit: contain' /></div></td>"
				output += "<td>"+item.region+"</td>"
				output += "<td>"+item.subregion+"</td>"
				output += "<td style='text-align:right'>"+addCommas(item.population)+"</td>"
				output += "<td>"
				    $.each(item.languages, function(b, languages) {
					output += languages.name+"<br />"
				    })
				output += "</td>"
				count += 1
			    })
			    output += "<tr><td colspan='7'><br /></td><td>Total: "+count+"</td></tr>"
			}
			$("#search_form #search_results table tbody").html(output);
			if(output_error){
			    $("#search_form #search_error").html(output_error);
			}
		}, 'json');
	});
    });
</script>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="form-style" id="search_form">
    <div class="form-style-heading"></div>
    <div id="search_body">
        <label><span>Name <span class="required">*</span></span>
            <input type="text" name="name" id="name" required="true" class="input-field"/>
	    <select name="search_type" id="search_type">
		<option value="name">Name</option>
		<option value="full_name">Full Name</option>
		<option value="alpha">Alpha</option>
	    </select>
        </label>
        <label>
            <span>&nbsp;</span><input type="submit" id="submit_btn" value="Submit" />
        </label>
    </div>
    <div id="search_error"></div>
    <div id="search_results">
	<table WIDTH='100%'>
		<thead>
			<th align='left'>Name</th>
			<th align='left'>Alpha code 2</th>
			<th align='left'>Alpha code 3</th>
			<th align='left'>Flag</th>
			<th align='left'>Region</th>
			<th align='left'>Sub Region</th>
			<th align='right'>Population</th>
			<th align='left'>Languages</th>
		</thead>
		<tbody></tbody>
	</table>
    </div>
</div>

</body>
</html>
