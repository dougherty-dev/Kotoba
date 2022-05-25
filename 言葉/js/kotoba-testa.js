/* jshint esversion: 6 */

(function() { // 言葉 testa
	"use strict";

	$("#testa_från_grad").selectmenu();
	$("#testa_från_grad").on("selectmenuchange", function(e){
		$.post("/ajax/testa_glosor.php", {
			testa_från_grad: $("#testa_från_grad").val()
		}).done(function(){
			$("#testa_från_grad" + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("input[name=testa_glosor]").on("click", function(){
		$.post("/ajax/testa_glosor.php", {valt_typid: $(this).val()});
	});

	$("#testa_glosor").on("click", function(e){
		$("#flikar-kotoba, #fliklista").hide();
		e.preventDefault();
		$.post("/ajax/testa_glosor.php", {
			typid: $("input[name=testa_glosor]:checked").val()
		}).done(function(data){
			$("#testyta").replaceWith(data);
			testa_glosor();
		});
	});

	function testa_glosor() {
		tangenter();

		$("button").button();
		$("input[type=radio], input[type=checkbox]").checkboxradio();

		$(".testrad, .testhuvud").hide();
		$("#test_nästa, #test_visa, #test_klart").hide();

		$("input[type=checkbox]").on("change", function(){
			$(this).blur();
			$.post("/ajax/testa_glosor.php", {
				id: $(this).prop("id"),
				ifylld: $(this).prop("checked") ? 1 : 0
			});
		});

		$("#test_börja").on("click", function(){
			$("#test_börja").remove();
			$("#test_visa").show();
			$(".testhuvud").show();
			$("#testtabell tr.testrad").first().show();
			redigera_grad();
			skifta_färg();
			tala_glosa();
		});

		$("#test_visa").on("click", function(){
			räknare();
			$("#test_nästa").show();
			$("#test_visa").hide();

			if ($("#från_översättning").prop("checked")) {
				$(".glostest_glosa").first().addClass("synlig").removeClass("transparent");
				$(".glostest_romanisering").first().addClass("synlig").removeClass("transparent");
			} else {
				$(".glostest_översättning").first().addClass("synlig").removeClass("transparent");
			}

			if ($("#ljud").prop("checked")) {
				$.post("/ajax/glosor.php", {
					tala_glosa: $(".glostest_glosa").first().text()
				});
			}
		});

		$("#test_nästa").on("click", function(){
			$("#testtabell tr.testrad").first().remove();
			$("#testtabell tr.testrad").first().show();
			$("#test_nästa").hide();
			$("#test_visa").show();
		});

		$("#test_klart").on("click", function(){
			window.location.replace('/');
		});

		$("#från_översättning").on("change", function(){
			skifta_färg();
		});
	}

	function tala_glosa() {
		$("#testyta").on("click", ".glostest_romanisering, .glostest_glosa", function(e){
			e.preventDefault();
			$.post("/ajax/glosor.php", {
				tala_glosa: $(this).text()
			});
		});
	}

	function räknare() {
		var kvar = $("#kvar").text();
		if (kvar > 1) {
			$("#kvar").text(--kvar);
		} else {
			$("#kvar").text("0");
			$("#test_nästa, #test_visa").remove();
			$("#test_klart").show();
		}
	}

	function skifta_färg() {
		if ($("#från_översättning").prop("checked")) {
			$(".glostest_översättning").addClass("synlig").removeClass("transparent");
			$(".glostest_romanisering, .glostest_glosa").addClass("transparent").removeClass("synlig");
		} else {
			$(".glostest_översättning").addClass("transparent").removeClass("synlig");
			$(".glostest_romanisering, .glostest_glosa").addClass("synlig").removeClass("transparent");
		}
	}

	function tangenter() {
		$("body").keydown(function(e) {
			e.preventDefault();
			if (e.keyCode === 32) {
				if ($("#test_börja").is(":visible")) {
					$("#test_börja").click();
				} else if ($("#test_visa").is(":visible")) {
					$("#test_visa").click();
				} else if ($("#test_nästa").is(":visible")) {
					$("#test_nästa").click();
				} else if ($("#test_klart").is(":visible")) {
					$("#test_klart").click();
				}
			} else if (e.keyCode === 27) {
				window.location.replace('/');
			}
		});
	}

	function redigera_grad() {
		$("#testyta").on("change", ".glostest_grad", function(e){
			e.preventDefault();
			var t = $(this);
			$.post("/ajax/glosor.php", {
				ändra_glosa_glosid: t.prev(".glostest_glosid").val(),
				kolumn: 'grad',
				värde: t.val()
			}).done(function(){
				t.fadeTo("slow", 0.5).fadeTo("slow", 1.0);
				t.blur();
			});
		});
	}

})(); // 言葉 testa
