<?php

require_once 'icfDatabase.php';

$testAdo = new AdoTest();
$testAdo->clean();
$testAdo->execute();
$testAdo->query();

/**
 * Inserts basic data into the database, testing ADO
 */
class AdoTest
{
	var $icfDatabase;
	
	function AdoTest()
	{
		$this->icfDatabase = new IcfDatabase();
	}
	
	/**
	 * Cleans the database
	 */
	function clean()
	{
		$conn = $this->icfDatabase->dbOpen();
		$conn->debug = true;
		$conn->StartTrans();
		
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Permission", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##ObjectAttribute", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##ObjectFolder", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Object", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##FolderClass", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##ClassRelation", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##ClassAttribute", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Class", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Folder WHERE title LIKE 'Media'", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Folder WHERE title LIKE 'Image'", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Folder WHERE title LIKE 'Countries'", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Folder WHERE title LIKE 'Columns'", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Folder WHERE title LIKE 'Articles'", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Folder WHERE title LIKE 'ICF News'", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Folder WHERE parentID IS NULL", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Datatype", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##RoleUser", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Action", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Role", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##User", $conn);
		$this->icfDatabase->dbExecuteInTx("DELETE FROM ##Language", $conn);
		
		$conn->CompleteTrans();
		$this->icfDatabase->dbClose($conn);	
	}
	
	/**
	 * Executes the inserts
	 */
	function execute()
	{				
		$conn = $this->icfDatabase->dbOpen();
		$conn->debug = true;
		$conn->StartTrans();
		
		$userId = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##User (name, nick, pwd, attributesID) VALUES ('diegoesp', 'diego', 'password', NULL)", $conn);
		$id = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##User (name, nick, pwd, attributesID) VALUES ('carlosjac', 'carloss', 'password', NULL)", $conn);
		$id = $this->icfDatabase->dbExecuteInTx("UPDATE ##User SET nick = 'carlos' WHERE id = " . $id, $conn);	
		// Roles
		echo "Roles<br>";
		$roleId = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Role (role, isDefault) VALUES ('admin', 0)", $conn);	
		// Roles for user
		echo "RoleUser<br>";
		$id = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##RoleUser (userId, roleId) VALUES (" . $userId . ", " . $roleId . ")", $conn);
		// Languages
		$idSpanish = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Language (title, code, isMain) VALUES ('Spanish', 'SP', 1)", $conn);
		$idEnglish = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Language (title, code, isMain) VALUES ('English', 'EN', 0)", $conn);
		// Actions
		echo "Actions<br>";
		$idAddObjects = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('ADD_OBJECTS')", $conn);
		$idListObjects = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('LIST_OBJECTS')", $conn);
		$idViewObjects = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('VIEW_OBJECTS')", $conn);
		$idEditObjects = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('EDIT_OBJECTS')", $conn);
		$idRemoveObjects = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('REMOVE_OBJECTS')", $conn);
		$idPublishObjects = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('PUBLISH_OBJECTS')", $conn);
		$idAddFolders = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('ADD_FOLDERS')", $conn);
		$idListFolders = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('LIST_FOLDERS')", $conn);
		$idViewFolders = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('VIEW_FOLDERS')", $conn);
		$idEditFolders = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('EDIT_FOLDERS')", $conn);
		$idRemoveFolders = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Action (action) VALUES ('REMOVE_FOLDERS')", $conn);
		// DataTypes
		echo "DataTypes<br>";
		$idText = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Text')", $conn);
		$idMemo = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Memo')", $conn);
		$idHtml = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('HTML')", $conn);
		$idInteger = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Integer')", $conn);
		$idDecimal = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Decimal')", $conn);
		$idDate = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Date')", $conn);
		$idTime = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Time')", $conn);
		$idUser = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('User')", $conn);
		$idBoolean = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Boolean')", $conn);
		$idEmail = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Email')", $conn);
		$idUrl = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('URL')", $conn);
		$idMedia = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Media')", $conn);
		$idImage = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('Image')", $conn);
		$idUploadImage = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('UploadImage')", $conn);
		$idUploadMedia = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Datatype (datatype) VALUES ('UploadMedia')", $conn);
		// Folders
		echo "Folders<br>";
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Folder (ID, parentID, title, shortDescription, longDescription, position) VALUES (1, NULL, 'Root', 'Root folder', 'Base folder for the system',  1)", $conn);
		$idRootFolder = 1;
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Folder (ID, parentID, title, shortDescription, longDescription, position) VALUES (2, ". $idRootFolder . ", 'IMAGE', 'Available images', 'Available images for the sites',  1)", $conn);
		$idImageFolder = 2;
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Folder (ID, parentID, title, shortDescription, longDescription, position) VALUES (3, ". $idRootFolder . ", 'MEDIA', 'Available media files', 'Available media files for the sites',  2)", $conn);
		$idMediaFolder = 3;
		$idNewsFolder = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Folder (parentID, title, shortDescription, longDescription, position) VALUES (". $idRootFolder . ", 'ICF News', 'News web site', 'Web site news with the latest IT info available',  3)", $conn);
		$idArticlesFolder = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Folder (parentID, title, shortDescription, longDescription, position) VALUES (" . $idNewsFolder . ", 'Articles', 'Articles given in this site', 'These are the news that the agency can get on his own',  1)", $conn);
		$idColumnsFolder = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Folder (parentID, title, shortDescription, longDescription, position) VALUES (". $idNewsFolder . ", 'Columns', 'Journalist columns', 'Columns written by specialized journalists or specific reports about some content',  2)", $conn);
		$idCountriesFolder = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Folder (parentID, title, shortDescription, longDescription, position) VALUES (". $idRootFolder . ", 'Countries', 'Available countries', 'Available countries for the sites',  4)", $conn);		
		// Classes
		echo "Classes<br>";
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Class (ID, title, shortDescription, longDescription, className, descriptor) VALUES (1, 'Image', 'Available images', 'These are the available images', NULL, '<TITLE>')", $conn);
		$idImageClass = 1;
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Class (ID, title, shortDescription, longDescription, className, descriptor) VALUES (2, 'Media', 'Available media files', 'These are the available media files', NULL, '<TITLE>')", $conn);
		$idMediaClass = 2;
		$idNews = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Class (title, shortDescription, longDescription, className, descriptor) VALUES ('News', 'News saved in the site', 'These are the news that the agency publishes', 'NewsService', '<TITLE>, <TITLE>')", $conn);
		$idCountry = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##Class (title, shortDescription, longDescription, className, descriptor) VALUES ('Country', 'Available countries', 'These are the available countries', NULL, '<NAME>')", $conn);
		// ClassAttributes
		echo "ClassAttributes<br>";
		$idNewsAttOne = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idText . "', 'TITLE', 'Title', 'The title of this news', '255', NULL, '1', '1', '0', 1)", $conn);
		$idNewsAttTwo = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idMemo . "', 'INTRO', 'Introduction', 'The introduction of this news', '1024', NULL, '1', '1', '1', 2)", $conn);
		$idNewsAttThree = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idHtml . "', 'BODY', 'Body', 'The body of this news', '4096', NULL, '1', '1', '1', 3)", $conn);
		$idNewsAttFour = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idInteger . "', 'ESTIMATED_READ_MINUTES', 'Estimated minutes', 'Quantity of minutes needed to read this article', '4', NULL, '1', 'false', 'false', 4)", $conn);
		$idNewsAttFive = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idDecimal . "', 'ESTIMATED_COST_DOLLARS', 'Estimated cost', 'Cost of this article', '8', NULL, 'false', 'false', 'false', 5)", $conn);		
		$idNewsAttSix = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idDate . "', 'PAID_DATE', 'Paid date', 'The date when the news must be paid', '10', NULL, '1', 'false', 'false', 6)", $conn);
		$idNewsAttSeven = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idTime . "', 'EVENT_DATETIME', 'Event datetime', 'The estimated date and time when the event happened', '18', NULL, '1', 'false', 'false', 7)", $conn);
		$idNewsAttEight = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idBoolean . "', 'COVER', 'Must be at the cover', 'If the article should be at the cover of the magazine', '1', NULL, '1', '1', 'false', 8)", $conn);
		$idNewsAttNine = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idEmail . "', 'CONTACT_EMAIL', 'Contact e-mail', 'News contact email', '255', NULL, '1', '1', 'false', 9)", $conn);
		$idNewsAttTen = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idUser . "', 'USER_DATAENTRY', 'User data entry', 'User data entry', '255', NULL, '1', '1', 'false', 10)", $conn);
		$idNewsAttEleven = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idMedia . "', 'RECORDING', 'Event Recording', 'Event recording', '99999', NULL, '1', '1', 'false', 10)", $conn);
		$idNewsAttTwelve = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idNews . "', '" . $idImage . "', 'IMAGE', 'Event Image', 'Event Image', '99999', NULL, '1', '1', 'false', 10)", $conn);
		
		$idCountryAttOne = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idCountry . "', '" . $idText . "', 'NAME', 'Country name', 'Country names available', '255', NULL, '1', '1', '1', 1)", $conn);
		
		$idImageAttOne = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idImageClass . "', '" . $idText . "', 'TITLE', 'Image title', 'Image title', '255', NULL, '1', '1', '1', 1)", $conn);
		$idImageAttTwo = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idImageClass . "', '" . $idUploadImage . "', 'IMAGE', 'Image', 'Image', '999999', NULL, '1', '0', '0', 1)", $conn);

		$idMediaAttOne = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idMediaClass . "', '" . $idText . "', 'TITLE', 'Media title', 'Media title', '255', NULL, '1', '1', '1', 1)", $conn);
		$idMediaAttTwo = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassAttribute (classID, datatypeID, name, title, helpText, len, defaultValue, isRequired, isSearchable, isTranslatable, position) VALUES ('" . $idMediaClass . "', '" . $idUploadMedia . "', 'MEDIA', 'Media', 'Media', '999999', NULL, '1', '0', '0', 1)", $conn);
		
		// ClassRelations
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##ClassRelation (parentID, childID, position, cardinality, title, helpText, isRequired) VALUES (" . $idNews . ", " . $idCountry . ", 1, 2, 'Country news', 'Country where the news was collected', 1)", $conn);
		
		// FoldersClasses		
		$newsRootFolderClass = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idNews . "', '" . $idRootFolder . "', '1', '1')", $conn);
		$countryRootFolderClass = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idCountry . "', '" . $idRootFolder . "', '2', '1')", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idCountry . "', '" . $idCountriesFolder . "', '3', '0')", $conn);
		$imageRootFolderClass = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idImageClass . "', '" . $idRootFolder . "', '4', '0')", $conn);
		$mediaRootFolderClass = $this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idMediaClass . "', '" . $idRootFolder . "', '5', '0')", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idImageClass . "', '" . $idImageFolder . "', '4', '0')", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idMediaClass . "', '" . $idMediaFolder . "', '5', '0')", $conn);
				
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idNews . "', '" . $idArticlesFolder . "', '1', '1')", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##FolderClass (classID, folderID, position, isDefault) VALUES ('". $idNews . "', '" . $idColumnsFolder . "', '2', '0')", $conn);		
				
		// Permissions
		echo "Permissions<br>";

		// Permission $newsRootFolderClass
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idAddObjects . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListObjects . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idViewObjects . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idEditObjects . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idRemoveObjects . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idPublishObjects . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListObjects . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idAddFolders . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListFolders . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idViewFolders . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idEditFolders . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idRemoveFolders . "', '" . $newsRootFolderClass . "', '" . $roleId . "', true)", $conn);

		// Permission $countryRootFolderClass
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idAddObjects . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListObjects . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idViewObjects . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idEditObjects . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idRemoveObjects . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idPublishObjects . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListObjects . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idAddFolders . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListFolders . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idViewFolders . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idEditFolders . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idRemoveFolders . "', '" . $countryRootFolderClass . "', '" . $roleId . "', true)", $conn);

		// Permission $imageRootFolderClass
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idAddObjects . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListObjects . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idViewObjects . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idEditObjects . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idRemoveObjects . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idPublishObjects . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListObjects . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idAddFolders . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListFolders . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idViewFolders . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idEditFolders . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idRemoveFolders . "', '" . $imageRootFolderClass . "', '" . $roleId . "', true)", $conn);

		
		// Permission $mediaRootFolderClass
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idAddObjects . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListObjects . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idViewObjects . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idEditObjects . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idRemoveObjects . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idPublishObjects . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListObjects . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idAddFolders . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idListFolders . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idViewFolders . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idEditFolders . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);
		$this->icfDatabase->dbExecuteInTx("INSERT INTO ##Permission (actionID, folderClassID, roleID, includeChildren) VALUES ('". $idRemoveFolders . "', '" . $mediaRootFolderClass . "', '" . $roleId . "', true)", $conn);

		$conn->CompleteTrans();
		$this->icfDatabase->dbClose($conn);	
	}
	
	/**
	 * Executes some queries to test the state of the database
	 */
	function query()
	{
		$rs = $this->icfDatabase->dbQuery('select * from ##User');
		
		print "<pre>";
		print_r($rs->GetRows());
		print "</pre>";		
	}
}

?>