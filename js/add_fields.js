$(function(){
		var nbIteration = 1;
		var detailIteration = 1;
		$("#addIteration").click(function(){
			nbIteration++;
		var detailIteration = 1;
			$("#content-contact").append('<div class="iteration"><h3>Track '+ nbIteration +'<span class="controlButtons"><input type="button" value="+" class="plus" /><input type="button" value="-" class="moins" /></span></h3><input type="text" name="Track'+ nbIteration +'" value="Track Title" /></div>');
		});
		$("#removeIteration").click(function(){
			if($(".iteration").length > 0){
				nbIteration--;
				$(".iteration").last().remove();
			}
		});
		$("#content-contact").on("click", ".plus",function(){
			var parent = $(this).closest(".iteration");
			parent.append('<input type="text" value="Track Details" name="Track_'+ nbIteration + '_' + detailIteration +'"/>');
			detailIteration++;
			var nbinput = parent.find("input[type='text']").length;
			if(nbinput == 5)
				parent.find(".plus").prop("disabled",true);
			if(nbinput > 0)
				parent.find(".moins").prop("disabled",false);
		});
		$("#content-contact").on("click",".moins", function(){ 
			var parent = $(this).closest(".iteration");
			parent.children("input").last().remove();
			var nbinput = parent.find("input[type='text']").length;
			if(nbinput < 5)
				parent.find(".plus").prop("disabled",false);
			if(nbinput == 0)
				parent.find(".moins").prop("disabled",true);
		});
	});