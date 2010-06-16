/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2010, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 *
 */

/**
 * @fileOverview Defines the {@link CKFinder.lang} object, for the French
 *		language. This is the base file for all translations.
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKFinder.lang['fr'] =
{
	appTitle : 'CKFinder', // MISSING

	// Common messages and labels.
	common :
	{
		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING
		ok				: 'OK',
		cancel			: 'Annuler',
		confirmationTitle	: 'Confirmation', // MISSING
		messageTitle	: 'Information', // MISSING
		inputTitle		: 'Question', // MISSING
		undo			: 'Annuler',
		redo			: 'Rétablir',
		skip			: 'Skip', // MISSING
		skipAll			: 'Skip all', // MISSING
		makeDecision	: 'What action should be taken?', // MISSING
		rememberDecision: 'Remember my decision'  // MISSING
	},


	dir : 'ltr',
	HelpLang : 'en',
	LangCode : 'fr',

	// Date Format
	//		d    : Day
	//		dd   : Day (padding zero)
	//		m    : Month
	//		mm   : Month (padding zero)
	//		yy   : Year (two digits)
	//		yyyy : Year (four digits)
	//		h    : Hour (12 hour clock)
	//		hh   : Hour (12 hour clock, padding zero)
	//		H    : Hour (24 hour clock)
	//		HH   : Hour (24 hour clock, padding zero)
	//		M    : Minute
	//		MM   : Minute (padding zero)
	//		a    : Firt char of AM/PM
	//		aa   : AM/PM
	DateTime : 'dd/mm/yyyy H:MM',
	DateAmPm : ['AM', 'PM'],

	// Folders
	FoldersTitle	: 'Dossiers',
	FolderLoading	: 'Chargement...',
	FolderNew		: 'Entrez le nouveau nom du dossier: ',
	FolderRename	: 'Entrez le nouveau nom du dossier: ',
	FolderDelete	: 'Êtes-vous sûr de vouloir effacer le dossier "%1" ?',
	FolderRenaming	: ' (Renommage en cours...)',
	FolderDeleting	: ' (Suppression en cours...)',

	// Files
	FileRename		: 'Entrez le nouveau nom du fichier: ',
	FileRenameExt	: 'Êtes-vous sûr de vouloir ¨changer l\'extension de ce fichier? Le fichier pourrait devenir inutilisable',
	FileRenaming	: 'Renommage en cours...',
	FileDelete		: 'Êtes-vous sûr de vouloir effacer le fichier "%1" ?',
	FilesLoading	: 'Loading...', // MISSING
	FilesEmpty		: 'Empty folder', // MISSING
	FilesMoved		: 'File %1 moved into %2:%3', // MISSING
	FilesCopied		: 'File %1 copied into %2:%3', // MISSING

	// Basket
	BasketFolder		: 'Basket', // MISSING
	BasketClear			: 'Clear Basket', // MISSING
	BasketRemove		: 'Remove from basket', // MISSING
	BasketOpenFolder	: 'Open parent folder', // MISSING
	BasketTruncateConfirm : 'Do you really want to remove all files from the basket?', // MISSING
	BasketRemoveConfirm	: 'Do you really want to remove the file "%1" from the basket?', // MISSING
	BasketEmpty			: 'No files in the basket, drag\'n\'drop some.', // MISSING
	BasketCopyFilesHere	: 'Copy Files from Basket', // MISSING
	BasketMoveFilesHere	: 'Move Files from Basket', // MISSING

	BasketPasteErrorOther	: 'File %s error: %e', // MISSING
	BasketPasteMoveSuccess	: 'The following files were moved: %s', // MISSING
	BasketPasteCopySuccess	: 'The following files were copied: %s', // MISSING

	// Toolbar Buttons (some used elsewhere)
	Upload		: 'Téléverser',
	UploadTip	: 'Téléverser un nouveau fichier',
	Refresh		: 'Rafraîchir',
	Settings	: 'Configuration',
	Help		: 'Aide',
	HelpTip		: 'Aide',

	// Context Menus
	Select			: 'Choisir',
	SelectThumbnail : 'Choisir une miniature',
	View			: 'Voir',
	Download		: 'Télécharger',

	NewSubFolder	: 'Nouveau sous-dossier',
	Rename			: 'Renommer',
	Delete			: 'Effacer',

	CopyDragDrop	: 'Copy file here', // MISSING
	MoveDragDrop	: 'Move file here', // MISSING

	// Dialogs
	RenameDlgTitle		: 'Rename', // MISSING
	NewNameDlgTitle		: 'New name', // MISSING
	FileExistsDlgTitle	: 'File already exists', // MISSING
	SysErrorDlgTitle : 'System error', // MISSING

	FileOverwrite	: 'Overwrite', // MISSING
	FileAutorename	: 'Auto-rename', // MISSING

	// Generic
	OkBtn		: 'OK',
	CancelBtn	: 'Annuler',
	CloseBtn	: 'Fermer',

	// Upload Panel
	UploadTitle			: 'Téléverser un nouveau fichier',
	UploadSelectLbl		: 'Sélectionner le fichier à téléverser',
	UploadProgressLbl	: '(Téléversement en cours, veuillez patienter...)',
	UploadBtn			: 'Téléverser le fichier sélectionné',
	UploadBtnCancel		: 'Cancel', // MISSING

	UploadNoFileMsg		: 'Sélectionner un fichier sur votre ordinateur',
	UploadNoFolder		: 'Please select folder before uploading.', // MISSING
	UploadNoPerms		: 'File upload not allowed.', // MISSING
	UploadUnknError		: 'Error sending the file.', // MISSING
	UploadExtIncorrect	: 'File extension not allowed in this folder.', // MISSING

	// Settings Panel
	SetTitle		: 'Configuration',
	SetView			: 'Voir:',
	SetViewThumb	: 'Miniatures',
	SetViewList		: 'Liste',
	SetDisplay		: 'Affichage:',
	SetDisplayName	: 'Nom du fichier',
	SetDisplayDate	: 'Date',
	SetDisplaySize	: 'Taille du fichier',
	SetSort			: 'Classement:',
	SetSortName		: 'par Nom de Fichier',
	SetSortDate		: 'par Date',
	SetSortSize		: 'par Taille',

	// Status Bar
	FilesCountEmpty : '<Dossier Vide>',
	FilesCountOne	: '1 fichier',
	FilesCountMany	: '%1 fichiers',

	// Size and Speed
	Kb				: '%1 kB', // MISSING
	KbPerSecond		: '%1 kB/s', // MISSING

	// Connector Error Messages.
	ErrorUnknown	: 'La demande n\'a pas abouti. (Erreur %1)',
	Errors :
	{
	 10 : 'Commande invalide.',
	 11 : 'Le type de ressource n\'a pas été spécifié dans la commande.',
	 12 : 'Le type de ressource n\'est pas valide.',
	102 : 'Nom de fichier ou de dossier invalide.',
	103 : 'La demande n\'a pas abouti : problème d\'autorisations.',
	104 : 'La demande n\'a pas abouti : problème de restrictions de permissions.',
	105 : 'Extension de fichier invalide.',
	109 : 'Demande invalide.',
	110 : 'Erreur inconnue.',
	115 : 'Un fichier ou un dossier avec ce nom existe déjà.',
	116 : 'Ce dossier n\'existe pas. Veuillez rafraîchir la page et réessayer.',
	117 : 'Ce fichier n\'existe pas. Veuillez rafraîchir la page et réessayer.',
	118 : 'Source and target paths are equal.', // MISSING
	201 : 'Un fichier avec ce nom existe déjà. Le fichier téléversé a été renommé en "%1"',
	202 : 'Fichier invalide',
	203 : 'Fichier invalide. La taille est trop grande.',
	204 : 'Le fichier téléversé est corrompu.',
	205 : 'Aucun dossier temporaire n\'est disponible sur le serveur.',
	206 : 'Téléversement interrompu pour raisons de sécurité. Le fichier contient des données de type HTML.',
	207 : 'El fichero subido ha sido renombrado como "%1"',
	300 : 'Moving file(s) failed.', // MISSING
	301 : 'Copying file(s) failed.', // MISSING
	500 : 'L\'interface de gestion des fichiers est désactivé. Contactez votre administrateur et vérifier le fichier de configuration de CKFinder.',
	501 : 'La fonction "miniatures" est désactivée.'
	},

	// Other Error Messages.
	ErrorMsg :
	{
		FileEmpty		: 'Le nom du fichier ne peut être vide',
		FileExists		: 'File %s already exists', // MISSING
		FolderEmpty		: 'Le nom du dossier ne peut être vide',

		FileInvChar		: 'Le nom du fichier ne peut pas contenir les charactères suivants : \n\\ / : * ? " < > |',
		FolderInvChar	: 'Le nom du dossier ne peut pas contenir les charactères suivants : \n\\ / : * ? " < > |',

		PopupBlockView	: 'Il n\'a pas été possible d\'ouvrir la nouvelle fenêtre. Désactiver votre bloqueur de fenêtres pour ce site.'
	},

	// Imageresize plugin
	Imageresize :
	{
		dialogTitle		: 'Resize %s', // MISSING
		sizeTooBig		: 'Cannot set image height or width to a value bigger than the original size (%size).', // MISSING
		resizeSuccess	: 'Image resized successfully.', // MISSING
		thumbnailNew	: 'Create new thumbnail', // MISSING
		thumbnailSmall	: 'Small (%s)', // MISSING
		thumbnailMedium	: 'Medium (%s)', // MISSING
		thumbnailLarge	: 'Large (%s)', // MISSING
		newSize			: 'Set new size', // MISSING
		width			: 'Width', // MISSING
		height			: 'Height', // MISSING
		invalidHeight	: 'Invalid height.', // MISSING
		invalidWidth	: 'Invalid width.', // MISSING
		invalidName		: 'Invalid file name.', // MISSING
		newImage		: 'Create new image', // MISSING
		noExtensionChange : 'The file extension cannot be changed.', // MISSING
		imageSmall		: 'Source image is too small',  // MISSING
		contextMenuName	: 'Resize' // MISSING
	},

	// Fileeditor plugin
	Fileeditor :
	{
		save			: 'Save', // MISSING
		fileOpenError	: 'Unable to open file.', // MISSING
		fileSaveSuccess	: 'File saved successfully.', // MISSING
		contextMenuName	: 'Edit', // MISSING
		loadingFile		: 'Loading file, please wait...' // MISSING
	}
};
