Forms are at /swap_model/admin/admin.php  and login.php

Process goes

admin.php POST --> actions.php (Clean, validate user input) --> functions.php (queries, stuff) --> admin.php (completed)    [ Ideal ]
				   |                                             |
				   |                                             |
				   |                                             | 
				   |                                             |
				   --> Catch error if any,                       |
				   redirect to admin.php or Error page.          --> Catch error if any 
			