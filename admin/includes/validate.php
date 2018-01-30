<?php
session_start();

function validateOps($operation)
{
	if(!empty($operation))
	{
		//If operation  
		if ($operation === 'create' || $operation === 'update' || $operation === 'login' || $operation === 'otp' || $operation === 'password' || $operation === 'comment')
		{			
			return false;
		}
		else
		{
			$_SESSION['staffErr'] = "Invalid (operation)";
			return true;
		}
	}
	else
	{
		return true;
	}
	

}
function validateUsers($username)
{
	//Allows only characters and numbers
	if (empty($username) || !preg_match("/^([A-Za-z0-9])+\z/", $username))
	{
		$_SESSION['staffregister'] = "Invalid inputs. username";
		return false;
	}
	else
	{
		return true;
	}
}

function validatePwd($password)
{
	//Enforce password complexity. If password is empty or length of password < 8 or does not match pattern, invalid.
	if (empty($password) || strlen($password) < 8 || !preg_match("/.*^(?=.{8,50})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/", $password))
	{
		$_SESSION['staffregister'] = "Invalid inputs. Password(s)";
		return false;
	}
	else
	{
		return true;
	}
}

function validateEmail($email)
{

	if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) 
	{
		$_SESSION['staffregister'] = "Invalid inputs. Email";
		return false;
	}
	else
	{
		return true;
	}
}

function validateName($name)
{
	//Allows only characters
	
	if(empty($name) || !preg_match("/^([A-Za-z])+\z/", $name)) 
	{
		$_SESSION['staffregister'] = "Invalid inputs. Name";
		return false;
	}
	else
	{
		return true;
	}
}
function validateRole($role)
{
	//Ensure only two possible roles
	if (!empty($role) && ($role === 'auditor' || $role === 'manager'))
	{
		return true;
	}
	else
	{
		$_SESSION['staffregister'] = "Invalid inputs. Role";
		return false;
	}
}

function validateStatus($status)
{
	//Ensure only two possible status
	if (!empty($status) && ($status == 'Activated' || $status == 'Disabled'))
	{
		return true;
	}
	else
	{
		$_SESSION['staffregister'] = "Invalid inputs. status";
		return false;
	}
}

function validateID($id)
{
	//Matches for numbers only
	if (empty($id) || !preg_match("/^([0-9])+\z/", $id)) 
	{
		$_SESSION['logsIDErr'] = "Invalid inputs. LogID";
		return false;
	}
	else
	{
		return true;
	}
}

function validateComment($comment)
{
	//Matches for numbers only
	if (empty($comment)) 
	{
		$_SESSION['logsIDErr'] = "Invalid inputs. Comment";
		return false;
	}
	else
	{
		return true;
	}
}
?>