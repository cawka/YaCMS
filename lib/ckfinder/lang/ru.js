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
 * @fileOverview Defines the {@link CKFinder.lang} object, for the Russian
 *		language. This is the base file for all translations.
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKFinder.lang['ru'] =
{
	appTitle : 'CKFinder', // MISSING

	// Common messages and labels.
	common :
	{
		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING
		ok				: 'OK', // MISSING
		cancel			: 'Cancel', // MISSING
		confirmationTitle	: 'Confirmation', // MISSING
		messageTitle	: 'Information', // MISSING
		inputTitle		: 'Question', // MISSING
		undo			: 'Undo', // MISSING
		redo			: 'Redo', // MISSING
		skip			: 'Skip', // MISSING
		skipAll			: 'Skip all', // MISSING
		makeDecision	: 'What action should be taken?', // MISSING
		rememberDecision: 'Remember my decision'  // MISSING
	},


	dir : 'ltr', // MISSING
	HelpLang : 'en',
	LangCode : 'ru',

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
	DateTime : 'm/d/yyyy h:MM aa',
	DateAmPm : ['AM', 'PM'],

	// Folders
	FoldersTitle	: 'Папки',
	FolderLoading	: 'Загрузка...',
	FolderNew		: 'Пожалуйста, введите новое имя папки: ',
	FolderRename	: 'Пожалуйста, введите новое имя папки: ',
	FolderDelete	: 'Вы уверены, что хотите удалить папку "%1"?',
	FolderRenaming	: ' (Переименовываю...)',
	FolderDeleting	: ' (Удаляю...)',

	// Files
	FileRename		: 'Пожалуйста, введите новое имя файла: ',
	FileRenameExt	: 'Вы уверены, что хотите изменить расширение файла? Файл может стать недоступным',
	FileRenaming	: 'Переименовываю...',
	FileDelete		: 'Вы уверены, что хотите удалить файл "%1"?',
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
	Upload		: 'Загрузка',
	UploadTip	: 'Загрузить новый файл',
	Refresh		: 'Обновить',
	Settings	: 'Установки',
	Help		: 'Помощь',
	HelpTip		: 'Помощь',

	// Context Menus
	Select			: 'Выбрать',
	SelectThumbnail : 'Select Thumbnail', // MISSING
	View			: 'Посмотреть',
	Download		: 'Сохранить',

	NewSubFolder	: 'Новая папка',
	Rename			: 'Переименовать',
	Delete			: 'Удалить',

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
	OkBtn		: 'ОК',
	CancelBtn	: 'Отмена',
	CloseBtn	: 'Закрыть',

	// Upload Panel
	UploadTitle			: 'Загрузить новый файл',
	UploadSelectLbl		: 'Выбрать файл для загрузки',
	UploadProgressLbl	: '(Загрузка в процессе, пожалуйста подождите...)',
	UploadBtn			: 'Загрузить выбранный файл',
	UploadBtnCancel		: 'Cancel', // MISSING

	UploadNoFileMsg		: 'Пожалуйста, выберите файл на вашем компьютере',
	UploadNoFolder		: 'Please select folder before uploading.', // MISSING
	UploadNoPerms		: 'File upload not allowed.', // MISSING
	UploadUnknError		: 'Error sending the file.', // MISSING
	UploadExtIncorrect	: 'File extension not allowed in this folder.', // MISSING

	// Settings Panel
	SetTitle		: 'Установки',
	SetView			: 'Просмотр:',
	SetViewThumb	: 'Эскизы',
	SetViewList		: 'Список',
	SetDisplay		: 'Отобразить:',
	SetDisplayName	: 'Имя файла',
	SetDisplayDate	: 'Дата',
	SetDisplaySize	: 'Размер файла',
	SetSort			: 'Сортировка:',
	SetSortName		: 'по Имени файла',
	SetSortDate		: 'по Дате',
	SetSortSize		: 'по Размеру',

	// Status Bar
	FilesCountEmpty : '<Пустая папка>',
	FilesCountOne	: '1 файл',
	FilesCountMany	: '%1 файлов',

	// Size and Speed
	Kb				: '%1 kB',
	KbPerSecond		: '%1 kB/s',

	// Connector Error Messages.
	ErrorUnknown	: 'Невозможно завершить запрос. (Ошибка %1)',
	Errors :
	{
	 10 : 'Неверная команда.',
	 11 : 'Тип ресурса не указан в запросе.',
	 12 : 'Неверный запрошенный тип ресурса.',
	102 : 'Неверное имя файла или папки.',
	103 : 'Невозможно завершить запрос из-за ограничений авторизации.',
	104 : 'Невозможно завершить запрос из-за ограничения разрешений файловой системы.',
	105 : 'Неверное расширение файла.',
	109 : 'Неверный запрос.',
	110 : 'Неизвестная ошибка.',
	115 : 'Файл или папка с таким именем уже существует.',
	116 : 'Папка не найдена. Пожалуйста, обновите вид папок и попробуйте еще раз.',
	117 : 'Файл не найден. Пожалуйста, обновите список файлов и попробуйте еще раз.',
	118 : 'Source and target paths are equal.', // MISSING
	201 : 'Файл с таким именем уже существует. Загруженный файл был переименован в "%1"',
	202 : 'Неверный файл',
	203 : 'Неверный файл. Размер файла слишком большой.',
	204 : 'Загруженный файл поврежден.',
	205 : 'Не доступна временная папка для загрузки файлов на сервер.',
	206 : 'Загрузка отменена из-за соображений безопасности. Файл содердит данные похожие на HTML.',
	207 : 'Загруженный файл был переименован в "%1"',
	300 : 'Moving file(s) failed.', // MISSING
	301 : 'Copying file(s) failed.', // MISSING
	500 : 'Браузер файлов отключен из-за соображений безопасности. Пожалуйста, сообщите вашему системному администратру и проверьте конфигурационный файл CKFinder.',
	501 : 'Поддержка эскизов отключена.'
	},

	// Other Error Messages.
	ErrorMsg :
	{
		FileEmpty		: 'Имя файла не может быть пустым',
		FileExists		: 'File %s already exists', // MISSING
		FolderEmpty		: 'Имя папки не может быть пустым',

		FileInvChar		: 'Имя файла не может содержать любой из перечисленных символов: \n\\ / : * ? " < > |',
		FolderInvChar	: 'Имя папки не может содержать любой из перечисленных символов: \n\\ / : * ? " < > |',

		PopupBlockView	: 'Невозможно открыть файл в новом окне. Пожалуйста, проверьте настройки браузера и отключите все блокировки всплывающих окон для этого сайта.'
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
