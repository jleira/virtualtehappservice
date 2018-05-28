<?php
	
function idioma()
{
$caso=2;
if ($caso==1) {return 'en';}
else {return 'es';}
}
function tienepermisos($permisos)
{
	$perfiles=array_map('intval', explode(',', Auth::user()->perfiles));
	foreach ($permisos as $permiso) {
	if(in_array($permiso, $perfiles)){
		return true;
	}	
}


}