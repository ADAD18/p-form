function pf_add( elemento ){
    var str = document.getElementById( "supports").value;
    var supports = str.split( ',' );

    //console.log( supports );
    if( elemento.checked ){
        supports.push( elemento.value );
    }else{
        supports.splice( supports.indexOf( elemento.value ), 1 );
    }
    //console.log( supports );
    document.getElementById( "supports").value = supports;
}

function iconSelect( element ){
    var icon = document.getElementById( "pf-icon" );
    icon.className = "dashicons " + element;
    console.log( element );
}
