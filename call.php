<?php

if(function_exists('call_isop')==false){
    function call_isop($op=""){
        switch($op){
            case "!":
            case "==":
            case "!=":
            case "<>":
            case "<":
            case ">":
            case "<=":
            case ">=":
            case "===":
            case "!==":
            case "<=>":
            case "+":
            case "-":
            case "*":
            case "/":
            case "&&":
            case "||":
            case ".":return true;
            default:return false;
        }
    }
}

if(function_exists('call_op')==false){
    function call_op($op="",$a="",$b=""){
        switch($op){
            case "!":return !(bool)$a;
            case "==":return $a==$b;
            case "!=":return $a!=$b;
            case "<>":return $a<>$b;
            case "<":return $a<$b;
            case ">":return $a>$b;
            case "<=":return $a<=$b;
            case ">=":return $a>=$b;
            case "===":return $a===$b;
            case "!==":return $a!==$b;
            case "<=>":return $a<=>$b;
            case "+":$ret=$a[0];for($i=1;$i<count($a);$i++){$ret=($ret + $a[$i]);}return $ret;
            case "-":return $a-$b;
            case "*":$ret=$a[0];for($i=1;$i<count($a);$i++){$ret=($ret * $a[$i]);}return $ret;
            case "/":return $a/$b;
            case "&&":$ret=$a[0];for($i=1;$i<count($a);$i++){$ret=($ret && $a[$i]);}return $ret;
            case "||":$ret=$a[0];for($i=1;$i<count($a);$i++){$ret=($ret || $a[$i]);}return $ret;
            case ".":return implode("",$a);
            default:return $op;
            
        }
    }
}

if(function_exists('call_array_para')==false){
    function call_array_para(){
        $ret=array();
        $a=func_get_args();
        $i=true;
        $key="";
        $val="";
        foreach($a as $v){
            if($i){
                $key=$v;
            }
            else{
                $val=$v;
                $ret[$key]=$val;
            }
            $i=!$i;
        }
        return $ret;
    }
}

if(function_exists('call_array')==false){
    function call_array(){
        $a=func_get_args();
        return $a;
    }
}
if(function_exists('call_explode')==false){
	function call_explode($r=",",$expr="",$e=""){
	    $out=array();
	    $first=true;
	    $br=0;
	    $sl=0;
	    $c=strlen($expr);
	    $x="";
	    for($i=0;$i<$c;$i++){
	        $s=substr($expr,$i,1);
	        if($s==$r){
                if($sl){
                    $sl=0;
                    $x.=$s;
                }
                else{
                    if($e<>''){
                        if($first){
                            $ss=substr($expr,$i,6);
                            if($ss=="(call:"){
                                $first=false;
                                $out[]=$x;
                                $x="call:";
                                $i+=5;
                            }
                        }                        
                    }
                    else{
        	            if($br==0){
        	                $out[]=$x;
        	                $x="";
        	            }
        	            else{
        	                $x.=$s;
        	            }
                    }
                }
	           continue; 
	        }
	        else{
	            if($e<>'' and !$first){
	                if($s==$e){
                        if($sl){
                            $sl=0;
                            $x.=$s;
                        }
                        else{
                            $out[]=$x;
                            $x="";
                            $first=true;
                        }
                        continue;
	                }
	            }
	        }
            switch($s){
	            case "(":
                    if($sl==0){
    	                $br++;
                    }
                    $sl=0;
	                $x.=$s;
	                break;
	            case ")":
                    if($sl==0){
    	                $br--;
                    }
                    $sl=0;
	                $x.=$s;
	                break;
	            case '\\':
                    if($sl){
                        $sl=0;
                        $x.=$s;
                    }
                    else{
    	                $sl++;
                    }
	                break;
	            default:
	                $x.=$s;
	                break;
	        }
	    }
	    if($x<>'')$out[]=$x;
	    $c=count($out);
	    for($i=0;$i<$c;$i++){
	        if(substr($out[$i],0,1)=="("){
	            $n=strlen($out[$i]);
	            if(substr($out[$i],$n-1,1)==")"){
	                $out[$i]=substr($out[$i],1,$n-2);
	            }
	        }
	    }
	    return $out;
	}
}

if(function_exists('call_exec')==false){
	function call_exec($expr="",$param1='',$param2=''){
	    if(!is_string($expr))return $expr;
	    if($expr=='')return '';
	    if(substr($expr,0,5)<>'call:'){
	        if(strpos($expr,"(call:")!==false){
                $x=call_explode("(",$expr,")");
                foreach($x as $kk=>$xx){
                    $x[$kk]=call_exec($xx);
                }
                return implode("",$x);
    	    }
	        return $expr;
	    }
	    $expr=substr($expr,5);
	    $x=call_explode(",",$expr);
	    $c=count($x);
	    for($i=0;$i<$c;$i++){
	        $x1=$x[$i];
	        if(defined($x1))$x1=constant($x1);
	        else{
	            if($x1=="true")$x1=true;
	            else{
	                if($x1=="false")$x1=false;
	            } 
	        }
	        if(is_array($param1)){
	            foreach($param1 as $k=>$v){
          	        $x1=str_replace($k,(string)$v,$x1);
	            }     
	        }
	        else{
    	        $x1=str_replace("param1",(string)$param1,$x1);
    	        $x1=str_replace("param2",(string)$param2,$x1);
	        }
            $x[$i]=call_exec($x1,$param1,$param2);
	    }
	    $x1=$x[0];
	    if($c>1){
	        if(call_isop($x1)){
	            if($x1=="." or $x1=="||" or $x1=="&&" or $x1=="+" or $x1=="*"){
	                $op=$x1;
	                $x1=array_slice($x,1);
	                $x1=call_op($op,$x1);
	            }
	            else{
            	    if($c==2){
            	        $x1=call_op($x1,$x[1]);
            	    }
        	        else{
            	        if($c==3){
            	            $x1=call_op($x1,$x[1],$x[2]);
            	        }
    	            }
	            }
	            return $x1;
	        }
	    }
	    return call_user_func_array($x1,array_slice($x,1));
	}
}	

?>