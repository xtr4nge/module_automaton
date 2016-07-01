function setCheckbox(item, param) {
    if (document.getElementById(item.id).checked) {
        value = "1";
    } else {
        value = "0";
    }
    $.getJSON('../api/includes/ws_action.php?api=/config/module/automaton/'+param+'/'+value, function(data) {});
}

function setOption(item, param) {
	value = $("#"+item).val();
    $.getJSON('../api/includes/ws_action.php?api=/config/module/automaton/'+param+'/'+value, function(data) {});
}

function setRadio(item, param) {
    value = document.getElementById(item.id).value
	//console.log(document.getElementById(item.id).checked);
	//console.log(document.getElementById(item.id).value);
    $.getJSON('../api/includes/ws_action.php?api=/config/module/automaton/'+param+'/'+value, function(data) {});
}

// STORE OPTIONS (Checkbox)
function setOptionCheckbox(id) {
    data = []
    $("#"+id+" input:checked").each(function() {                    
        //console.log($(this).attr('value'))
        data.push($(this).attr('value'))
        })
    
    value = data.join(",")
    console.log(value)
    
    //value = $("#"+id).val()
    console.log(id + "|" + value)
    
    $.getJSON('../api/includes/ws_action.php?api=/config/module/automaton/mod_automaton_'+id+'/'+value, function(data) {});
    
}

function setOptionSelect(item, param) {
	var e = document.getElementById(item.id);
	var value = e.options[e.selectedIndex].text;
	
    $.getJSON('../api/includes/ws_action.php?api=/config/module/automaton/'+param+'/'+value, function(data) {});
}