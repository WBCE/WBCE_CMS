function toggle() {
	var check = document.getElementById("file2");
	if (check.style.visibility == "visible") {
		for (i=2; i<=10; i++) {
			document.getElementById("file" + i).style.visibility = "hidden";
		}
		document.getElementById("delzip").style.display = "inline";
	} else {
		for (i=2; i<=10; i++) {
			document.getElementById("file" + i).style.visibility = "visible";
		}
		document.getElementById("delzip").style.display = "none";
	}
}
