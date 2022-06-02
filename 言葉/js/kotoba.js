/* jshint esversion: 6 */

(function() { // 言葉
	"use strict";

	$("#flikar").tabs();
	$("input[type=submit], a, button").button();
	$("input[type=radio], input[type=checkbox]").checkboxradio();

	/* ============ preferenser ============ */
	$("#uppdatera_röster").on("click", function(e){
		e.preventDefault();
		$.post("/ajax/preferenser.php", {
			uppdatera_röster: true
		}).done(function(){
			window.location.replace('/');
		});
	});

	$("#dammsug_databas").on("click", function(e){
		e.preventDefault();
		$.post("/ajax/preferenser.php", {
			dammsug_databas: true
		}).done(function(){
			$("#dammsug_databas").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#säkerhetskopiera").on("click", function(e){
		e.preventDefault();
		$.post("/ajax/preferenser.php", {
			säkerhetskopiera: true
		}).done(function(){
			$("#säkerhetskopiera").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	/* ============ språk ============ */
	$(".välj_språk_språkid").selectmenu();
	$("#redigera_språk_röst").selectmenu();
	$("#nytt_språk_röst").selectmenu();

	$(".välj_språk_språkid").on("selectmenuchange", function(e){
		e.preventDefault();
		$.post("/ajax/språk.php", {
			välj_språk_språkid: $(this).val()
		}).done(function(){
			window.location.replace('/');
		});
	});

	$("#nytt_språk").on("click", function(e){
		e.preventDefault();
		$.post("/ajax/språk.php", {
			nytt_språk_språk: $("#nytt_språk_språk").val(),
			nytt_språk_lokalspråk: $("#nytt_språk_lokalspråk").val(),
			nytt_språk_romanisering: $("#nytt_språk_romanisering").val(),
			nytt_språk_röst: $("#nytt_språk_röst").val()
		}).done(function(){
			window.location.replace('/');
		});
	});

	$("#radera_språk").on("click", function(e){
		e.preventDefault();
		var radera_språk_språkid = $("#redigera_språk_språkid").val();
		var radera_språk_språknamn = $("#redigera_språk_språknamn").val();
		if (confirm('Radera språk ' + radera_språk_språkid + ': ' + radera_språk_språknamn)) {
			$.post("/ajax/språk.php", {
				radera_språk_språkid: radera_språk_språkid
			}).done(function(){
				window.location.replace('/');
			});
		}
	});

	$("#redigera_språk_språknamn").on("change", function(e){
		e.preventDefault();
		$.post("/ajax/språk.php", {
			redigera_språk_språkid: $("#redigera_språk_språkid").val(),
			redigera_språk_språknamn: $("#redigera_språk_språknamn").val()
		}).done(function(){
			$("#redigera_språk_språknamn").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#redigera_språk_lokalspråk").on("change", function(e){
		e.preventDefault();
		$.post("/ajax/språk.php", {
			redigera_språk_språkid: $("#redigera_språk_språkid").val(),
			redigera_språk_lokalspråk: $("#redigera_språk_lokalspråk").val()
		}).done(function(){
			$("#redigera_språk_lokalspråk").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#redigera_språk_romanisering").on("change", function(e){
		e.preventDefault();
		$.post("/ajax/språk.php", {
			redigera_språk_språkid: $("#redigera_språk_språkid").val(),
			redigera_språk_romanisering: $("#redigera_språk_romanisering").val()
		}).done(function(){
			$("#redigera_språk_romanisering").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#redigera_språk_röst").on("selectmenuchange", function(e){
		e.preventDefault();
		$.post("/ajax/språk.php", {
			redigera_språk_språkid: $("#redigera_språk_språkid").val(),
			redigera_språk_röst: $("#redigera_språk_röst").val()
		}).done(function(){
			$("#redigera_språk_röst" + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	/* ============ böcker ============ */
	$(".välj_bok_bokid").selectmenu();

	$(".välj_bok_bokid").on("selectmenuchange", function(e){
		e.preventDefault();
		$.post("/ajax/böcker.php", {
			välj_bok_bokid: $(this).val()
		}).done(function(){
			window.location.replace('/');
		});
	});

	$("#ny_bok").on("click", function(e){
		e.preventDefault();
		$.post("/ajax/böcker.php", {
			ny_bok_boknamn: $("#ny_bok_boknamn").val()
		}).done(function(){
			window.location.replace('/');
		});
	});

	$("#radera_bok").on("click", function(e){
		e.preventDefault();
		var radera_bok_bokid = $("#redigera_böcker_bokid").val();
		var radera_bok_boknamn = $("#redigera_böcker_boknamn").val();
		if (confirm('Radera bok ' + radera_bok_bokid + ': ' + radera_bok_boknamn)) {
			$.post("/ajax/böcker.php", {
				radera_bok_bokid: radera_bok_bokid
			}).done(function(){
				window.location.replace('/');
			});
		}
	});

	$("#redigera_böcker_boknamn").on("change", function(e){
		e.preventDefault();
		$.post("/ajax/böcker.php", {
			redigera_böcker_bokid: $("#redigera_böcker_bokid").val(),
			redigera_böcker_boknamn: $("#redigera_böcker_boknamn").val()
		}).done(function(){
			$("#redigera_böcker_boknamn").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	/* ============ kapitel ============ */
	$(".välj_kapitel_kapitelid").selectmenu();

	$(".välj_kapitel_kapitelid").on("selectmenuchange", function(e){
		e.preventDefault();
		$.post("/ajax/kapitel.php", {
			välj_kapitel_kapitelid: $(this).val()
		}).done(function(){
			window.location.replace('/');
		});
	});

	$("#nytt_kapitel").on("click", function(e){
		e.preventDefault();
		$.post("/ajax/kapitel.php", {
			nytt_kapitel_kapitelnamn: $("#nytt_kapitel_kapitelnamn").val()
		}).done(function(){
			window.location.replace('/');
		});
	});

	$("#radera_kapitel").on("click", function(e){
		e.preventDefault();
		var radera_kapitel_kapitelid = $("#redigera_kapitel_kapitelid").val();
		var radera_kapitel_kapitelnamn = $("#redigera_kapitel_kapitelnamn").val();
		if (confirm('Radera kapitel ' + radera_kapitel_kapitelid + ': ' + radera_kapitel_kapitelnamn)) {
			$.post("/ajax/kapitel.php", {
				radera_kapitel_kapitelid: radera_kapitel_kapitelid
			}).done(function(){
				window.location.replace('/');
			});
		}
	});

	$("#redigera_kapitel_kapitelnamn").on("change", function(e){
		e.preventDefault();
		$.post("/ajax/kapitel.php", {
			redigera_kapitel_kapitelid: $("#redigera_kapitel_kapitelid").val(),
			redigera_kapitel_kapitelnamn: $("#redigera_kapitel_kapitelnamn").val()
		}).done(function(){
			$("#redigera_kapitel_kapitelnamn").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	/* ============ glosor ============ */
	$("#ny_glosa").on("click", function(e){
		e.preventDefault();
		$.post("/ajax/glosor.php", {
			ny_glosa_glosa: $("#ny_glosa_glosa").val(),
			ny_glosa_romanisering: $("#ny_glosa_romanisering").val(),
			ny_glosa_översättning: $("#ny_glosa_översättning").val(),
			ny_glosa_grad: $("#ny_glosa_grad").val()
		}).done(function(data){
			$("#ny_glosa_glosa").val('');
			$("#ny_glosa_romanisering").val('');
			$("#ny_glosa_översättning").val('');
			$("#ny_glosa_grad").val("3");
			$("#glosor").append(data);
			$("#ny_glosa_glosa").focus();
		});
	});

	$("#glosor").on("change", ".glosa, .romanisering, .översättning, .grad", function(e){
		e.preventDefault();
		var t = $(this);
		$.post("/ajax/glosor.php", {
			ändra_glosa_glosid: t.parents("tr").children("td").children("input").val(),
			kolumn: t.attr("class"),
			värde: t.val()
		}).done(function(){
			t.fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#glosor").on("click", ".radera_glosa", function(e){
		e.preventDefault();
		var t = $(this);
		$.post("/ajax/glosor.php", {
			radera_glosa_glosid: t.next().val()
		}).done(function(){
			t.parents("tr").remove();
		});
	});

	$("#glosor").on("click", ".tala_glosa", function(e){
		e.preventDefault();
		$.post("/ajax/glosor.php", {
			tala_glosa: $(this).parents("tr").find("input.glosa").val()
		});
	});

	$("#ny_glosa_översättning").keydown(function(e) {
		if (e.keyCode === 13) {
			$("#ny_glosa").trigger("click");
		}
	});
})(); // 言葉
