	function add_child_page(page_id)
    {
		//find and select the page in the parent dropdown
		var selectBox = document.add.parent;
		for (var i = 0; i < selectBox.options.length; i++)
		{
			  if (selectBox.options[i].value == page_id)
			  {
					selectBox.selectedIndex = i;
					break;
			  }
		}
		//set focus to add form
		document.add.title.focus();
	}
