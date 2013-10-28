function loadingmsg(dialogid,divid,title){
	if(divid!="" && typeof(divid)!=undefined){
		$('#'+divid).mask("Loading...");
	}
	else{
		$('#'+dialogid).dialog('option', 'position', 'top');
		if ($('#'+dialogid).dialog( "isOpen" )===true){
			if(title!="" && typeof(title)!=undefined){
				$('#'+dialogid).dialog('option','title',title);
			}
			$('#'+dialogid).mask("Loading...");
		}
		else{
			if(dialogid=="workflowdialog" || dialogid=="messagedialog" || dialogid=="notesviewdialog"){
				$('#'+dialogid).dialog('option','position',[getinnerWidth()-$("#buttontabs").offset().left,80]);
			}
			$('#'+dialogid).dialog('open');
			if(title!="" && typeof(title)!=undefined){
				$('#'+dialogid).dialog('option','title',title);
			}
			$('#'+dialogid).mask("Loading...");
		}
	}
}