jQuery("input").attr("onpaste","return false;");

    function vdr(e) {
        tecla = (document.all) ? e.keyCode : e.which;  
        if (tecla==8 && tecla.charAt(0) != 0){
             return true;
             patron = /^[0-9kK]$/;
                te = String.fromCharCode(tecla);
                return patron.test(te);
        }else{
                patron = /^[0-9kK]$/;
                te = String.fromCharCode(tecla);
                return patron.test(te);
            }
    }
    
     $(document).ready(function(){
                $('#r').focusout(function(){
                    var div1, div2, div3, div4;
                    var r = $(this).val();

                    if(r.length==9){    
                        div1=r.slice(0,2);
                        div2=r.slice(2,5);
                        div3=r.slice(5,8);
                        div4=r.slice(8,9);

                        $(this).val(div1 + "." + div2 + "." + div3 + "-" + div4);

                    }

                    if(r.length==8){    
                        div1=r.slice(0,1);
                        div2=r.slice(1,4);
                        div3=r.slice(4,7);
                        div4=r.slice(7,8);

                        $(this).val(div1 + "." + div2 + "." + div3 + "-" + div4);   
                    }  
                });
            });