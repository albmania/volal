<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for makina_shitje
 */
class MakinaShitje extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $mshitjeID;
    public $mshitjeMarka;
    public $mshitjeModeli;
    public $mshitjeTipi;
    public $mshitjeStruktura;
    public $mshitjeKapacitetiMotorrit;
    public $mshitjeVitiProdhimit;
    public $mshitjeKarburant;
    public $mshitjeNgjyra;
    public $mshitjeNrVendeve;
    public $mshitjeKambio;
    public $mshitjePrejardhja;
    public $mshitjeTargaAL;
    public $mshitjeKilometra;
    public $mshitjeFotografi;
    public $mshitjePershkrimi;
    public $mshitjeCmimi;
    public $mshitjeIndex;
    public $mshitjePromo;
    public $mshitjeAktiv;
    public $mshitjeShitur;
    public $mshitjeAutori;
    public $mshitjeKrijuar;
    public $mshitjeAzhornuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'makina_shitje';
        $this->TableName = 'makina_shitje';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`makina_shitje`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordVersion = 12; // Word version (PHPWord only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = "A4"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // mshitjeID
        $this->mshitjeID = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeID',
            'mshitjeID',
            '`mshitjeID`',
            '`mshitjeID`',
            3,
            255,
            -1,
            false,
            '`mshitjeID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->mshitjeID->InputTextType = "text";
        $this->mshitjeID->IsAutoIncrement = true; // Autoincrement field
        $this->mshitjeID->IsPrimaryKey = true; // Primary key field
        $this->mshitjeID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['mshitjeID'] = &$this->mshitjeID;

        // mshitjeMarka
        $this->mshitjeMarka = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeMarka',
            'mshitjeMarka',
            '`mshitjeMarka`',
            '`mshitjeMarka`',
            3,
            255,
            -1,
            false,
            '`mshitjeMarka`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->mshitjeMarka->InputTextType = "text";
        $this->mshitjeMarka->Nullable = false; // NOT NULL field
        $this->mshitjeMarka->Required = true; // Required field
        $this->mshitjeMarka->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->mshitjeMarka->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->mshitjeMarka->Lookup = new Lookup('mshitjeMarka', 'makina_marka', false, 'mmarkaID', ["mmarkaMarka","","",""], [], ["x_mshitjeModeli"], [], [], [], [], '', '', "`mmarkaMarka`");
        $this->mshitjeMarka->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->mshitjeMarka->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mshitjeMarka->Param, "CustomMsg");
        $this->Fields['mshitjeMarka'] = &$this->mshitjeMarka;

        // mshitjeModeli
        $this->mshitjeModeli = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeModeli',
            'mshitjeModeli',
            '`mshitjeModeli`',
            '`mshitjeModeli`',
            3,
            255,
            -1,
            false,
            '`mshitjeModeli`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->mshitjeModeli->InputTextType = "text";
        $this->mshitjeModeli->Nullable = false; // NOT NULL field
        $this->mshitjeModeli->Required = true; // Required field
        $this->mshitjeModeli->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->mshitjeModeli->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->mshitjeModeli->Lookup = new Lookup('mshitjeModeli', 'makina_modeli', false, 'mmodeliID', ["mmodeliModeli","","",""], ["x_mshitjeMarka"], ["x_mshitjeTipi"], ["mmodeliMarka"], ["x_mmodeliMarka"], [], [], '', '', "`mmodeliModeli`");
        $this->mshitjeModeli->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->mshitjeModeli->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mshitjeModeli->Param, "CustomMsg");
        $this->Fields['mshitjeModeli'] = &$this->mshitjeModeli;

        // mshitjeTipi
        $this->mshitjeTipi = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeTipi',
            'mshitjeTipi',
            '`mshitjeTipi`',
            '`mshitjeTipi`',
            3,
            255,
            -1,
            false,
            '`mshitjeTipi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->mshitjeTipi->InputTextType = "text";
        $this->mshitjeTipi->Nullable = false; // NOT NULL field
        $this->mshitjeTipi->Required = true; // Required field
        $this->mshitjeTipi->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->mshitjeTipi->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->mshitjeTipi->Lookup = new Lookup('mshitjeTipi', 'makina_tipi', false, 'mtipiID', ["mtipiTipi","","",""], ["x_mshitjeModeli"], [], ["mtipiModeli"], ["x_mtipiModeli"], [], [], '', '', "`mtipiTipi`");
        $this->mshitjeTipi->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->mshitjeTipi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mshitjeTipi->Param, "CustomMsg");
        $this->Fields['mshitjeTipi'] = &$this->mshitjeTipi;

        // mshitjeStruktura
        $this->mshitjeStruktura = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeStruktura',
            'mshitjeStruktura',
            '`mshitjeStruktura`',
            '`mshitjeStruktura`',
            202,
            9,
            -1,
            false,
            '`mshitjeStruktura`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mshitjeStruktura->InputTextType = "text";
        $this->mshitjeStruktura->Nullable = false; // NOT NULL field
        $this->mshitjeStruktura->Required = true; // Required field
        $this->mshitjeStruktura->Lookup = new Lookup('mshitjeStruktura', 'makina_shitje', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mshitjeStruktura->OptionCount = 9;
        $this->Fields['mshitjeStruktura'] = &$this->mshitjeStruktura;

        // mshitjeKapacitetiMotorrit
        $this->mshitjeKapacitetiMotorrit = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeKapacitetiMotorrit',
            'mshitjeKapacitetiMotorrit',
            '`mshitjeKapacitetiMotorrit`',
            '`mshitjeKapacitetiMotorrit`',
            200,
            25,
            -1,
            false,
            '`mshitjeKapacitetiMotorrit`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeKapacitetiMotorrit->InputTextType = "text";
        $this->mshitjeKapacitetiMotorrit->Nullable = false; // NOT NULL field
        $this->mshitjeKapacitetiMotorrit->Required = true; // Required field
        $this->mshitjeKapacitetiMotorrit->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mshitjeKapacitetiMotorrit->Param, "CustomMsg");
        $this->Fields['mshitjeKapacitetiMotorrit'] = &$this->mshitjeKapacitetiMotorrit;

        // mshitjeVitiProdhimit
        $this->mshitjeVitiProdhimit = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeVitiProdhimit',
            'mshitjeVitiProdhimit',
            '`mshitjeVitiProdhimit`',
            '`mshitjeVitiProdhimit`',
            3,
            4,
            -1,
            false,
            '`mshitjeVitiProdhimit`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeVitiProdhimit->InputTextType = "text";
        $this->mshitjeVitiProdhimit->Nullable = false; // NOT NULL field
        $this->mshitjeVitiProdhimit->Required = true; // Required field
        $this->mshitjeVitiProdhimit->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['mshitjeVitiProdhimit'] = &$this->mshitjeVitiProdhimit;

        // mshitjeKarburant
        $this->mshitjeKarburant = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeKarburant',
            'mshitjeKarburant',
            '`mshitjeKarburant`',
            '`mshitjeKarburant`',
            202,
            13,
            -1,
            false,
            '`mshitjeKarburant`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mshitjeKarburant->InputTextType = "text";
        $this->mshitjeKarburant->Nullable = false; // NOT NULL field
        $this->mshitjeKarburant->Required = true; // Required field
        $this->mshitjeKarburant->Lookup = new Lookup('mshitjeKarburant', 'makina_shitje', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mshitjeKarburant->OptionCount = 6;
        $this->Fields['mshitjeKarburant'] = &$this->mshitjeKarburant;

        // mshitjeNgjyra
        $this->mshitjeNgjyra = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeNgjyra',
            'mshitjeNgjyra',
            '`mshitjeNgjyra`',
            '`mshitjeNgjyra`',
            200,
            20,
            -1,
            false,
            '`mshitjeNgjyra`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeNgjyra->InputTextType = "text";
        $this->mshitjeNgjyra->Nullable = false; // NOT NULL field
        $this->mshitjeNgjyra->Required = true; // Required field
        $this->Fields['mshitjeNgjyra'] = &$this->mshitjeNgjyra;

        // mshitjeNrVendeve
        $this->mshitjeNrVendeve = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeNrVendeve',
            'mshitjeNrVendeve',
            '`mshitjeNrVendeve`',
            '`mshitjeNrVendeve`',
            200,
            10,
            -1,
            false,
            '`mshitjeNrVendeve`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeNrVendeve->InputTextType = "text";
        $this->mshitjeNrVendeve->Nullable = false; // NOT NULL field
        $this->mshitjeNrVendeve->Required = true; // Required field
        $this->Fields['mshitjeNrVendeve'] = &$this->mshitjeNrVendeve;

        // mshitjeKambio
        $this->mshitjeKambio = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeKambio',
            'mshitjeKambio',
            '`mshitjeKambio`',
            '`mshitjeKambio`',
            202,
            10,
            -1,
            false,
            '`mshitjeKambio`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mshitjeKambio->InputTextType = "text";
        $this->mshitjeKambio->Nullable = false; // NOT NULL field
        $this->mshitjeKambio->Required = true; // Required field
        $this->mshitjeKambio->Lookup = new Lookup('mshitjeKambio', 'makina_shitje', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mshitjeKambio->OptionCount = 4;
        $this->Fields['mshitjeKambio'] = &$this->mshitjeKambio;

        // mshitjePrejardhja
        $this->mshitjePrejardhja = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjePrejardhja',
            'mshitjePrejardhja',
            '`mshitjePrejardhja`',
            '`mshitjePrejardhja`',
            200,
            50,
            -1,
            false,
            '`mshitjePrejardhja`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjePrejardhja->InputTextType = "text";
        $this->mshitjePrejardhja->Nullable = false; // NOT NULL field
        $this->mshitjePrejardhja->Required = true; // Required field
        $this->mshitjePrejardhja->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mshitjePrejardhja->Param, "CustomMsg");
        $this->Fields['mshitjePrejardhja'] = &$this->mshitjePrejardhja;

        // mshitjeTargaAL
        $this->mshitjeTargaAL = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeTargaAL',
            'mshitjeTargaAL',
            '`mshitjeTargaAL`',
            '`mshitjeTargaAL`',
            202,
            2,
            -1,
            false,
            '`mshitjeTargaAL`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mshitjeTargaAL->InputTextType = "text";
        $this->mshitjeTargaAL->Nullable = false; // NOT NULL field
        $this->mshitjeTargaAL->Required = true; // Required field
        $this->mshitjeTargaAL->Lookup = new Lookup('mshitjeTargaAL', 'makina_shitje', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mshitjeTargaAL->OptionCount = 2;
        $this->mshitjeTargaAL->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mshitjeTargaAL->Param, "CustomMsg");
        $this->Fields['mshitjeTargaAL'] = &$this->mshitjeTargaAL;

        // mshitjeKilometra
        $this->mshitjeKilometra = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeKilometra',
            'mshitjeKilometra',
            '`mshitjeKilometra`',
            '`mshitjeKilometra`',
            3,
            7,
            -1,
            false,
            '`mshitjeKilometra`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeKilometra->InputTextType = "text";
        $this->mshitjeKilometra->Nullable = false; // NOT NULL field
        $this->mshitjeKilometra->Required = true; // Required field
        $this->mshitjeKilometra->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->mshitjeKilometra->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mshitjeKilometra->Param, "CustomMsg");
        $this->Fields['mshitjeKilometra'] = &$this->mshitjeKilometra;

        // mshitjeFotografi
        $this->mshitjeFotografi = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeFotografi',
            'mshitjeFotografi',
            '`mshitjeFotografi`',
            '`mshitjeFotografi`',
            201,
            65535,
            -1,
            true,
            '`mshitjeFotografi`',
            false,
            false,
            false,
            'IMAGE',
            'FILE'
        );
        $this->mshitjeFotografi->InputTextType = "text";
        $this->mshitjeFotografi->Nullable = false; // NOT NULL field
        $this->mshitjeFotografi->Required = true; // Required field
        $this->mshitjeFotografi->UploadAllowedFileExt = "jpg,jpeg,png,";
        $this->mshitjeFotografi->UploadMultiple = true;
        $this->mshitjeFotografi->Upload->UploadMultiple = true;
        $this->mshitjeFotografi->UploadMaxFileCount = 20;
        $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
        $this->Fields['mshitjeFotografi'] = &$this->mshitjeFotografi;

        // mshitjePershkrimi
        $this->mshitjePershkrimi = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjePershkrimi',
            'mshitjePershkrimi',
            '`mshitjePershkrimi`',
            '`mshitjePershkrimi`',
            201,
            9999,
            -1,
            false,
            '`mshitjePershkrimi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->mshitjePershkrimi->InputTextType = "text";
        $this->mshitjePershkrimi->Nullable = false; // NOT NULL field
        $this->mshitjePershkrimi->Required = true; // Required field
        $this->Fields['mshitjePershkrimi'] = &$this->mshitjePershkrimi;

        // mshitjeCmimi
        $this->mshitjeCmimi = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeCmimi',
            'mshitjeCmimi',
            '`mshitjeCmimi`',
            '`mshitjeCmimi`',
            200,
            20,
            -1,
            false,
            '`mshitjeCmimi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeCmimi->InputTextType = "text";
        $this->mshitjeCmimi->Nullable = false; // NOT NULL field
        $this->mshitjeCmimi->Required = true; // Required field
        $this->Fields['mshitjeCmimi'] = &$this->mshitjeCmimi;

        // mshitjeIndex
        $this->mshitjeIndex = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeIndex',
            'mshitjeIndex',
            '`mshitjeIndex`',
            '`mshitjeIndex`',
            202,
            2,
            -1,
            false,
            '`mshitjeIndex`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mshitjeIndex->InputTextType = "text";
        $this->mshitjeIndex->Nullable = false; // NOT NULL field
        $this->mshitjeIndex->Required = true; // Required field
        $this->mshitjeIndex->Lookup = new Lookup('mshitjeIndex', 'makina_shitje', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mshitjeIndex->OptionCount = 2;
        $this->Fields['mshitjeIndex'] = &$this->mshitjeIndex;

        // mshitjePromo
        $this->mshitjePromo = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjePromo',
            'mshitjePromo',
            '`mshitjePromo`',
            '`mshitjePromo`',
            202,
            2,
            -1,
            false,
            '`mshitjePromo`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mshitjePromo->InputTextType = "text";
        $this->mshitjePromo->Nullable = false; // NOT NULL field
        $this->mshitjePromo->Required = true; // Required field
        $this->mshitjePromo->Lookup = new Lookup('mshitjePromo', 'makina_shitje', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mshitjePromo->OptionCount = 2;
        $this->Fields['mshitjePromo'] = &$this->mshitjePromo;

        // mshitjeAktiv
        $this->mshitjeAktiv = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeAktiv',
            'mshitjeAktiv',
            '`mshitjeAktiv`',
            '`mshitjeAktiv`',
            202,
            2,
            -1,
            false,
            '`mshitjeAktiv`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mshitjeAktiv->InputTextType = "text";
        $this->mshitjeAktiv->Nullable = false; // NOT NULL field
        $this->mshitjeAktiv->Required = true; // Required field
        $this->mshitjeAktiv->Lookup = new Lookup('mshitjeAktiv', 'makina_shitje', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mshitjeAktiv->OptionCount = 2;
        $this->Fields['mshitjeAktiv'] = &$this->mshitjeAktiv;

        // mshitjeShitur
        $this->mshitjeShitur = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeShitur',
            'mshitjeShitur',
            '`mshitjeShitur`',
            '`mshitjeShitur`',
            202,
            2,
            -1,
            false,
            '`mshitjeShitur`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mshitjeShitur->InputTextType = "text";
        $this->mshitjeShitur->Nullable = false; // NOT NULL field
        $this->mshitjeShitur->Required = true; // Required field
        $this->mshitjeShitur->Lookup = new Lookup('mshitjeShitur', 'makina_shitje', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mshitjeShitur->OptionCount = 2;
        $this->Fields['mshitjeShitur'] = &$this->mshitjeShitur;

        // mshitjeAutori
        $this->mshitjeAutori = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeAutori',
            'mshitjeAutori',
            '`mshitjeAutori`',
            '`mshitjeAutori`',
            3,
            255,
            -1,
            false,
            '`mshitjeAutori`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeAutori->InputTextType = "text";
        $this->mshitjeAutori->Nullable = false; // NOT NULL field
        $this->mshitjeAutori->Lookup = new Lookup('mshitjeAutori', 'perdoruesit', false, 'perdID', ["perdEmri","","",""], [], [], [], [], [], [], '', '', "`perdEmri`");
        $this->mshitjeAutori->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['mshitjeAutori'] = &$this->mshitjeAutori;

        // mshitjeKrijuar
        $this->mshitjeKrijuar = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeKrijuar',
            'mshitjeKrijuar',
            '`mshitjeKrijuar`',
            CastDateFieldForLike("`mshitjeKrijuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`mshitjeKrijuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeKrijuar->InputTextType = "text";
        $this->mshitjeKrijuar->Nullable = false; // NOT NULL field
        $this->mshitjeKrijuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['mshitjeKrijuar'] = &$this->mshitjeKrijuar;

        // mshitjeAzhornuar
        $this->mshitjeAzhornuar = new DbField(
            'makina_shitje',
            'makina_shitje',
            'x_mshitjeAzhornuar',
            'mshitjeAzhornuar',
            '`mshitjeAzhornuar`',
            CastDateFieldForLike("`mshitjeAzhornuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`mshitjeAzhornuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mshitjeAzhornuar->InputTextType = "text";
        $this->mshitjeAzhornuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['mshitjeAzhornuar'] = &$this->mshitjeAzhornuar;

        // Add Doctrine Cache
        $this->Cache = new ArrayCache();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Multiple column sort
    public function updateSort(&$fld, $ctrl)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $lastOrderBy = in_array($lastSort, ["ASC", "DESC"]) ? $sortField . " " . $lastSort : "";
            $curOrderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            if ($ctrl) {
                $orderBy = $this->getSessionOrderBy();
                $arOrderBy = !empty($orderBy) ? explode(", ", $orderBy) : [];
                if ($lastOrderBy != "" && in_array($lastOrderBy, $arOrderBy)) {
                    foreach ($arOrderBy as $key => $val) {
                        if ($val == $lastOrderBy) {
                            if ($curOrderBy == "") {
                                unset($arOrderBy[$key]);
                            } else {
                                $arOrderBy[$key] = $curOrderBy;
                            }
                        }
                    }
                } elseif ($curOrderBy != "") {
                    $arOrderBy[] = $curOrderBy;
                }
                $orderBy = implode(", ", $arOrderBy);
                $this->setSessionOrderBy($orderBy); // Save to Session
            } else {
                $this->setSessionOrderBy($curOrderBy); // Save to Session
            }
        } else {
            if (!$ctrl) {
                $fld->setSort("");
            }
        }
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`makina_shitje`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            case "lookup":
                return (($allow & 256) == 256);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlwrk);
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
            // Get insert id if necessary
            $this->mshitjeID->setDbValue($conn->lastInsertId());
            $rs['mshitjeID'] = $this->mshitjeID->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('mshitjeID', $rs)) {
                AddFilter($where, QuotedName('mshitjeID', $this->Dbid) . '=' . QuotedValue($rs['mshitjeID'], $this->mshitjeID->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->mshitjeID->DbValue = $row['mshitjeID'];
        $this->mshitjeMarka->DbValue = $row['mshitjeMarka'];
        $this->mshitjeModeli->DbValue = $row['mshitjeModeli'];
        $this->mshitjeTipi->DbValue = $row['mshitjeTipi'];
        $this->mshitjeStruktura->DbValue = $row['mshitjeStruktura'];
        $this->mshitjeKapacitetiMotorrit->DbValue = $row['mshitjeKapacitetiMotorrit'];
        $this->mshitjeVitiProdhimit->DbValue = $row['mshitjeVitiProdhimit'];
        $this->mshitjeKarburant->DbValue = $row['mshitjeKarburant'];
        $this->mshitjeNgjyra->DbValue = $row['mshitjeNgjyra'];
        $this->mshitjeNrVendeve->DbValue = $row['mshitjeNrVendeve'];
        $this->mshitjeKambio->DbValue = $row['mshitjeKambio'];
        $this->mshitjePrejardhja->DbValue = $row['mshitjePrejardhja'];
        $this->mshitjeTargaAL->DbValue = $row['mshitjeTargaAL'];
        $this->mshitjeKilometra->DbValue = $row['mshitjeKilometra'];
        $this->mshitjeFotografi->Upload->DbValue = $row['mshitjeFotografi'];
        $this->mshitjePershkrimi->DbValue = $row['mshitjePershkrimi'];
        $this->mshitjeCmimi->DbValue = $row['mshitjeCmimi'];
        $this->mshitjeIndex->DbValue = $row['mshitjeIndex'];
        $this->mshitjePromo->DbValue = $row['mshitjePromo'];
        $this->mshitjeAktiv->DbValue = $row['mshitjeAktiv'];
        $this->mshitjeShitur->DbValue = $row['mshitjeShitur'];
        $this->mshitjeAutori->DbValue = $row['mshitjeAutori'];
        $this->mshitjeKrijuar->DbValue = $row['mshitjeKrijuar'];
        $this->mshitjeAzhornuar->DbValue = $row['mshitjeAzhornuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $this->mshitjeFotografi->OldUploadPath = '../ngarkime/makina/';
        $oldFiles = EmptyValue($row['mshitjeFotografi']) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $row['mshitjeFotografi']);
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->mshitjeFotografi->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->mshitjeFotografi->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`mshitjeID` = @mshitjeID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->mshitjeID->CurrentValue : $this->mshitjeID->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->mshitjeID->CurrentValue = $keys[0];
            } else {
                $this->mshitjeID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('mshitjeID', $row) ? $row['mshitjeID'] : null;
        } else {
            $val = $this->mshitjeID->OldValue !== null ? $this->mshitjeID->OldValue : $this->mshitjeID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@mshitjeID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("MakinaShitjeList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "MakinaShitjeView") {
            return $Language->phrase("View");
        } elseif ($pageName == "MakinaShitjeEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "MakinaShitjeAdd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "MakinaShitjeView";
            case Config("API_ADD_ACTION"):
                return "MakinaShitjeAdd";
            case Config("API_EDIT_ACTION"):
                return "MakinaShitjeEdit";
            case Config("API_DELETE_ACTION"):
                return "MakinaShitjeDelete";
            case Config("API_LIST_ACTION"):
                return "MakinaShitjeList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "MakinaShitjeList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("MakinaShitjeView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("MakinaShitjeView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "MakinaShitjeAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "MakinaShitjeAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("MakinaShitjeEdit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("MakinaShitjeAdd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("MakinaShitjeDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"mshitjeID\":" . JsonEncode($this->mshitjeID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->mshitjeID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->mshitjeID->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language;
        $sortUrl = "";
        $attrs = "";
        if ($fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-sort-url="' . $sortUrl . '" data-sort-type="2"';
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") . '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("mshitjeID") ?? Route("mshitjeID")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->mshitjeID->CurrentValue = $key;
            } else {
                $this->mshitjeID->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->mshitjeID->setDbValue($row['mshitjeID']);
        $this->mshitjeMarka->setDbValue($row['mshitjeMarka']);
        $this->mshitjeModeli->setDbValue($row['mshitjeModeli']);
        $this->mshitjeTipi->setDbValue($row['mshitjeTipi']);
        $this->mshitjeStruktura->setDbValue($row['mshitjeStruktura']);
        $this->mshitjeKapacitetiMotorrit->setDbValue($row['mshitjeKapacitetiMotorrit']);
        $this->mshitjeVitiProdhimit->setDbValue($row['mshitjeVitiProdhimit']);
        $this->mshitjeKarburant->setDbValue($row['mshitjeKarburant']);
        $this->mshitjeNgjyra->setDbValue($row['mshitjeNgjyra']);
        $this->mshitjeNrVendeve->setDbValue($row['mshitjeNrVendeve']);
        $this->mshitjeKambio->setDbValue($row['mshitjeKambio']);
        $this->mshitjePrejardhja->setDbValue($row['mshitjePrejardhja']);
        $this->mshitjeTargaAL->setDbValue($row['mshitjeTargaAL']);
        $this->mshitjeKilometra->setDbValue($row['mshitjeKilometra']);
        $this->mshitjeFotografi->Upload->DbValue = $row['mshitjeFotografi'];
        $this->mshitjePershkrimi->setDbValue($row['mshitjePershkrimi']);
        $this->mshitjeCmimi->setDbValue($row['mshitjeCmimi']);
        $this->mshitjeIndex->setDbValue($row['mshitjeIndex']);
        $this->mshitjePromo->setDbValue($row['mshitjePromo']);
        $this->mshitjeAktiv->setDbValue($row['mshitjeAktiv']);
        $this->mshitjeShitur->setDbValue($row['mshitjeShitur']);
        $this->mshitjeAutori->setDbValue($row['mshitjeAutori']);
        $this->mshitjeKrijuar->setDbValue($row['mshitjeKrijuar']);
        $this->mshitjeAzhornuar->setDbValue($row['mshitjeAzhornuar']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // mshitjeID

        // mshitjeMarka

        // mshitjeModeli

        // mshitjeTipi

        // mshitjeStruktura

        // mshitjeKapacitetiMotorrit

        // mshitjeVitiProdhimit

        // mshitjeKarburant

        // mshitjeNgjyra

        // mshitjeNrVendeve

        // mshitjeKambio

        // mshitjePrejardhja

        // mshitjeTargaAL

        // mshitjeKilometra

        // mshitjeFotografi

        // mshitjePershkrimi

        // mshitjeCmimi

        // mshitjeIndex

        // mshitjePromo

        // mshitjeAktiv

        // mshitjeShitur

        // mshitjeAutori

        // mshitjeKrijuar

        // mshitjeAzhornuar

        // mshitjeID
        $this->mshitjeID->ViewValue = $this->mshitjeID->CurrentValue;
        $this->mshitjeID->ViewCustomAttributes = "";

        // mshitjeMarka
        $curVal = strval($this->mshitjeMarka->CurrentValue);
        if ($curVal != "") {
            $this->mshitjeMarka->ViewValue = $this->mshitjeMarka->lookupCacheOption($curVal);
            if ($this->mshitjeMarka->ViewValue === null) { // Lookup from database
                $filterWrk = "`mmarkaID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->mshitjeMarka->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->mshitjeMarka->Lookup->renderViewRow($rswrk[0]);
                    $this->mshitjeMarka->ViewValue = $this->mshitjeMarka->displayValue($arwrk);
                } else {
                    $this->mshitjeMarka->ViewValue = FormatNumber($this->mshitjeMarka->CurrentValue, $this->mshitjeMarka->formatPattern());
                }
            }
        } else {
            $this->mshitjeMarka->ViewValue = null;
        }
        $this->mshitjeMarka->CssClass = "fw-bold";
        $this->mshitjeMarka->ViewCustomAttributes = "";

        // mshitjeModeli
        $curVal = strval($this->mshitjeModeli->CurrentValue);
        if ($curVal != "") {
            $this->mshitjeModeli->ViewValue = $this->mshitjeModeli->lookupCacheOption($curVal);
            if ($this->mshitjeModeli->ViewValue === null) { // Lookup from database
                $filterWrk = "`mmodeliID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->mshitjeModeli->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->mshitjeModeli->Lookup->renderViewRow($rswrk[0]);
                    $this->mshitjeModeli->ViewValue = $this->mshitjeModeli->displayValue($arwrk);
                } else {
                    $this->mshitjeModeli->ViewValue = FormatNumber($this->mshitjeModeli->CurrentValue, $this->mshitjeModeli->formatPattern());
                }
            }
        } else {
            $this->mshitjeModeli->ViewValue = null;
        }
        $this->mshitjeModeli->CssClass = "fw-bold";
        $this->mshitjeModeli->ViewCustomAttributes = "";

        // mshitjeTipi
        $curVal = strval($this->mshitjeTipi->CurrentValue);
        if ($curVal != "") {
            $this->mshitjeTipi->ViewValue = $this->mshitjeTipi->lookupCacheOption($curVal);
            if ($this->mshitjeTipi->ViewValue === null) { // Lookup from database
                $filterWrk = "`mtipiID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->mshitjeTipi->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->mshitjeTipi->Lookup->renderViewRow($rswrk[0]);
                    $this->mshitjeTipi->ViewValue = $this->mshitjeTipi->displayValue($arwrk);
                } else {
                    $this->mshitjeTipi->ViewValue = FormatNumber($this->mshitjeTipi->CurrentValue, $this->mshitjeTipi->formatPattern());
                }
            }
        } else {
            $this->mshitjeTipi->ViewValue = null;
        }
        $this->mshitjeTipi->CssClass = "fw-bold";
        $this->mshitjeTipi->ViewCustomAttributes = "";

        // mshitjeStruktura
        if (strval($this->mshitjeStruktura->CurrentValue) != "") {
            $this->mshitjeStruktura->ViewValue = $this->mshitjeStruktura->optionCaption($this->mshitjeStruktura->CurrentValue);
        } else {
            $this->mshitjeStruktura->ViewValue = null;
        }
        $this->mshitjeStruktura->ViewCustomAttributes = "";

        // mshitjeKapacitetiMotorrit
        $this->mshitjeKapacitetiMotorrit->ViewValue = $this->mshitjeKapacitetiMotorrit->CurrentValue;
        $this->mshitjeKapacitetiMotorrit->ViewCustomAttributes = "";

        // mshitjeVitiProdhimit
        $this->mshitjeVitiProdhimit->ViewValue = $this->mshitjeVitiProdhimit->CurrentValue;
        $this->mshitjeVitiProdhimit->ViewValue = FormatNumber($this->mshitjeVitiProdhimit->ViewValue, $this->mshitjeVitiProdhimit->formatPattern());
        $this->mshitjeVitiProdhimit->ViewCustomAttributes = "";

        // mshitjeKarburant
        if (strval($this->mshitjeKarburant->CurrentValue) != "") {
            $this->mshitjeKarburant->ViewValue = $this->mshitjeKarburant->optionCaption($this->mshitjeKarburant->CurrentValue);
        } else {
            $this->mshitjeKarburant->ViewValue = null;
        }
        $this->mshitjeKarburant->ViewCustomAttributes = "";

        // mshitjeNgjyra
        $this->mshitjeNgjyra->ViewValue = $this->mshitjeNgjyra->CurrentValue;
        $this->mshitjeNgjyra->ViewCustomAttributes = "";

        // mshitjeNrVendeve
        $this->mshitjeNrVendeve->ViewValue = $this->mshitjeNrVendeve->CurrentValue;
        $this->mshitjeNrVendeve->ViewCustomAttributes = "";

        // mshitjeKambio
        if (strval($this->mshitjeKambio->CurrentValue) != "") {
            $this->mshitjeKambio->ViewValue = $this->mshitjeKambio->optionCaption($this->mshitjeKambio->CurrentValue);
        } else {
            $this->mshitjeKambio->ViewValue = null;
        }
        $this->mshitjeKambio->ViewCustomAttributes = "";

        // mshitjePrejardhja
        $this->mshitjePrejardhja->ViewValue = $this->mshitjePrejardhja->CurrentValue;
        $this->mshitjePrejardhja->ViewCustomAttributes = "";

        // mshitjeTargaAL
        if (strval($this->mshitjeTargaAL->CurrentValue) != "") {
            $this->mshitjeTargaAL->ViewValue = $this->mshitjeTargaAL->optionCaption($this->mshitjeTargaAL->CurrentValue);
        } else {
            $this->mshitjeTargaAL->ViewValue = null;
        }
        $this->mshitjeTargaAL->ViewCustomAttributes = "";

        // mshitjeKilometra
        $this->mshitjeKilometra->ViewValue = $this->mshitjeKilometra->CurrentValue;
        $this->mshitjeKilometra->ViewValue = FormatNumber($this->mshitjeKilometra->ViewValue, $this->mshitjeKilometra->formatPattern());
        $this->mshitjeKilometra->ViewCustomAttributes = "";

        // mshitjeFotografi
        $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
        if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
            $this->mshitjeFotografi->ImageWidth = 100;
            $this->mshitjeFotografi->ImageHeight = 0;
            $this->mshitjeFotografi->ImageAlt = $this->mshitjeFotografi->alt();
            $this->mshitjeFotografi->ImageCssClass = "ew-image";
            $this->mshitjeFotografi->ViewValue = $this->mshitjeFotografi->Upload->DbValue;
        } else {
            $this->mshitjeFotografi->ViewValue = "";
        }
        $this->mshitjeFotografi->ViewCustomAttributes = "";

        // mshitjePershkrimi
        $this->mshitjePershkrimi->ViewValue = $this->mshitjePershkrimi->CurrentValue;
        $this->mshitjePershkrimi->ViewCustomAttributes = "";

        // mshitjeCmimi
        $this->mshitjeCmimi->ViewValue = $this->mshitjeCmimi->CurrentValue;
        $this->mshitjeCmimi->CssClass = "fw-bold";
        $this->mshitjeCmimi->ViewCustomAttributes = "";

        // mshitjeIndex
        if (strval($this->mshitjeIndex->CurrentValue) != "") {
            $this->mshitjeIndex->ViewValue = $this->mshitjeIndex->optionCaption($this->mshitjeIndex->CurrentValue);
        } else {
            $this->mshitjeIndex->ViewValue = null;
        }
        $this->mshitjeIndex->ViewCustomAttributes = "";

        // mshitjePromo
        if (strval($this->mshitjePromo->CurrentValue) != "") {
            $this->mshitjePromo->ViewValue = $this->mshitjePromo->optionCaption($this->mshitjePromo->CurrentValue);
        } else {
            $this->mshitjePromo->ViewValue = null;
        }
        $this->mshitjePromo->ViewCustomAttributes = "";

        // mshitjeAktiv
        if (strval($this->mshitjeAktiv->CurrentValue) != "") {
            $this->mshitjeAktiv->ViewValue = $this->mshitjeAktiv->optionCaption($this->mshitjeAktiv->CurrentValue);
        } else {
            $this->mshitjeAktiv->ViewValue = null;
        }
        $this->mshitjeAktiv->ViewCustomAttributes = "";

        // mshitjeShitur
        if (strval($this->mshitjeShitur->CurrentValue) != "") {
            $this->mshitjeShitur->ViewValue = $this->mshitjeShitur->optionCaption($this->mshitjeShitur->CurrentValue);
        } else {
            $this->mshitjeShitur->ViewValue = null;
        }
        $this->mshitjeShitur->ViewCustomAttributes = "";

        // mshitjeAutori
        $this->mshitjeAutori->ViewValue = $this->mshitjeAutori->CurrentValue;
        $curVal = strval($this->mshitjeAutori->CurrentValue);
        if ($curVal != "") {
            $this->mshitjeAutori->ViewValue = $this->mshitjeAutori->lookupCacheOption($curVal);
            if ($this->mshitjeAutori->ViewValue === null) { // Lookup from database
                $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->mshitjeAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->mshitjeAutori->Lookup->renderViewRow($rswrk[0]);
                    $this->mshitjeAutori->ViewValue = $this->mshitjeAutori->displayValue($arwrk);
                } else {
                    $this->mshitjeAutori->ViewValue = FormatNumber($this->mshitjeAutori->CurrentValue, $this->mshitjeAutori->formatPattern());
                }
            }
        } else {
            $this->mshitjeAutori->ViewValue = null;
        }
        $this->mshitjeAutori->ViewCustomAttributes = "";

        // mshitjeKrijuar
        $this->mshitjeKrijuar->ViewValue = $this->mshitjeKrijuar->CurrentValue;
        $this->mshitjeKrijuar->ViewValue = FormatDateTime($this->mshitjeKrijuar->ViewValue, $this->mshitjeKrijuar->formatPattern());
        $this->mshitjeKrijuar->ViewCustomAttributes = "";

        // mshitjeAzhornuar
        $this->mshitjeAzhornuar->ViewValue = $this->mshitjeAzhornuar->CurrentValue;
        $this->mshitjeAzhornuar->ViewValue = FormatDateTime($this->mshitjeAzhornuar->ViewValue, $this->mshitjeAzhornuar->formatPattern());
        $this->mshitjeAzhornuar->ViewCustomAttributes = "";

        // mshitjeID
        $this->mshitjeID->LinkCustomAttributes = "";
        $this->mshitjeID->HrefValue = "";
        $this->mshitjeID->TooltipValue = "";

        // mshitjeMarka
        $this->mshitjeMarka->LinkCustomAttributes = "";
        $this->mshitjeMarka->HrefValue = "";
        $this->mshitjeMarka->TooltipValue = "";

        // mshitjeModeli
        $this->mshitjeModeli->LinkCustomAttributes = "";
        $this->mshitjeModeli->HrefValue = "";
        $this->mshitjeModeli->TooltipValue = "";

        // mshitjeTipi
        $this->mshitjeTipi->LinkCustomAttributes = "";
        $this->mshitjeTipi->HrefValue = "";
        $this->mshitjeTipi->TooltipValue = "";

        // mshitjeStruktura
        $this->mshitjeStruktura->LinkCustomAttributes = "";
        $this->mshitjeStruktura->HrefValue = "";
        $this->mshitjeStruktura->TooltipValue = "";

        // mshitjeKapacitetiMotorrit
        $this->mshitjeKapacitetiMotorrit->LinkCustomAttributes = "";
        $this->mshitjeKapacitetiMotorrit->HrefValue = "";
        $this->mshitjeKapacitetiMotorrit->TooltipValue = "";

        // mshitjeVitiProdhimit
        $this->mshitjeVitiProdhimit->LinkCustomAttributes = "";
        $this->mshitjeVitiProdhimit->HrefValue = "";
        $this->mshitjeVitiProdhimit->TooltipValue = "";

        // mshitjeKarburant
        $this->mshitjeKarburant->LinkCustomAttributes = "";
        $this->mshitjeKarburant->HrefValue = "";
        $this->mshitjeKarburant->TooltipValue = "";

        // mshitjeNgjyra
        $this->mshitjeNgjyra->LinkCustomAttributes = "";
        $this->mshitjeNgjyra->HrefValue = "";
        $this->mshitjeNgjyra->TooltipValue = "";

        // mshitjeNrVendeve
        $this->mshitjeNrVendeve->LinkCustomAttributes = "";
        $this->mshitjeNrVendeve->HrefValue = "";
        $this->mshitjeNrVendeve->TooltipValue = "";

        // mshitjeKambio
        $this->mshitjeKambio->LinkCustomAttributes = "";
        $this->mshitjeKambio->HrefValue = "";
        $this->mshitjeKambio->TooltipValue = "";

        // mshitjePrejardhja
        $this->mshitjePrejardhja->LinkCustomAttributes = "";
        $this->mshitjePrejardhja->HrefValue = "";
        $this->mshitjePrejardhja->TooltipValue = "";

        // mshitjeTargaAL
        $this->mshitjeTargaAL->LinkCustomAttributes = "";
        $this->mshitjeTargaAL->HrefValue = "";
        $this->mshitjeTargaAL->TooltipValue = "";

        // mshitjeKilometra
        $this->mshitjeKilometra->LinkCustomAttributes = "";
        $this->mshitjeKilometra->HrefValue = "";
        $this->mshitjeKilometra->TooltipValue = "";

        // mshitjeFotografi
        $this->mshitjeFotografi->LinkCustomAttributes = "";
        $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
        if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
            $this->mshitjeFotografi->HrefValue = "%u"; // Add prefix/suffix
            $this->mshitjeFotografi->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->mshitjeFotografi->HrefValue = FullUrl($this->mshitjeFotografi->HrefValue, "href");
            }
        } else {
            $this->mshitjeFotografi->HrefValue = "";
        }
        $this->mshitjeFotografi->ExportHrefValue = $this->mshitjeFotografi->UploadPath . $this->mshitjeFotografi->Upload->DbValue;
        $this->mshitjeFotografi->TooltipValue = "";
        if ($this->mshitjeFotografi->UseColorbox) {
            if (EmptyValue($this->mshitjeFotografi->TooltipValue)) {
                $this->mshitjeFotografi->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->mshitjeFotografi->LinkAttrs["data-rel"] = "makina_shitje_x_mshitjeFotografi";
            $this->mshitjeFotografi->LinkAttrs->appendClass("ew-lightbox");
        }

        // mshitjePershkrimi
        $this->mshitjePershkrimi->LinkCustomAttributes = "";
        $this->mshitjePershkrimi->HrefValue = "";
        $this->mshitjePershkrimi->TooltipValue = "";

        // mshitjeCmimi
        $this->mshitjeCmimi->LinkCustomAttributes = "";
        $this->mshitjeCmimi->HrefValue = "";
        $this->mshitjeCmimi->TooltipValue = "";

        // mshitjeIndex
        $this->mshitjeIndex->LinkCustomAttributes = "";
        $this->mshitjeIndex->HrefValue = "";
        $this->mshitjeIndex->TooltipValue = "";

        // mshitjePromo
        $this->mshitjePromo->LinkCustomAttributes = "";
        $this->mshitjePromo->HrefValue = "";
        $this->mshitjePromo->TooltipValue = "";

        // mshitjeAktiv
        $this->mshitjeAktiv->LinkCustomAttributes = "";
        $this->mshitjeAktiv->HrefValue = "";
        $this->mshitjeAktiv->TooltipValue = "";

        // mshitjeShitur
        $this->mshitjeShitur->LinkCustomAttributes = "";
        $this->mshitjeShitur->HrefValue = "";
        $this->mshitjeShitur->TooltipValue = "";

        // mshitjeAutori
        $this->mshitjeAutori->LinkCustomAttributes = "";
        $this->mshitjeAutori->HrefValue = "";
        $this->mshitjeAutori->TooltipValue = "";

        // mshitjeKrijuar
        $this->mshitjeKrijuar->LinkCustomAttributes = "";
        $this->mshitjeKrijuar->HrefValue = "";
        $this->mshitjeKrijuar->TooltipValue = "";

        // mshitjeAzhornuar
        $this->mshitjeAzhornuar->LinkCustomAttributes = "";
        $this->mshitjeAzhornuar->HrefValue = "";
        $this->mshitjeAzhornuar->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // mshitjeID
        $this->mshitjeID->setupEditAttributes();
        $this->mshitjeID->EditCustomAttributes = "";
        $this->mshitjeID->EditValue = $this->mshitjeID->CurrentValue;
        $this->mshitjeID->ViewCustomAttributes = "";

        // mshitjeMarka
        $this->mshitjeMarka->setupEditAttributes();
        $this->mshitjeMarka->EditCustomAttributes = "";
        $this->mshitjeMarka->PlaceHolder = RemoveHtml($this->mshitjeMarka->caption());

        // mshitjeModeli
        $this->mshitjeModeli->setupEditAttributes();
        $this->mshitjeModeli->EditCustomAttributes = "";
        $this->mshitjeModeli->PlaceHolder = RemoveHtml($this->mshitjeModeli->caption());

        // mshitjeTipi
        $this->mshitjeTipi->setupEditAttributes();
        $this->mshitjeTipi->EditCustomAttributes = "";
        $this->mshitjeTipi->PlaceHolder = RemoveHtml($this->mshitjeTipi->caption());

        // mshitjeStruktura
        $this->mshitjeStruktura->EditCustomAttributes = "";
        $this->mshitjeStruktura->EditValue = $this->mshitjeStruktura->options(false);
        $this->mshitjeStruktura->PlaceHolder = RemoveHtml($this->mshitjeStruktura->caption());

        // mshitjeKapacitetiMotorrit
        $this->mshitjeKapacitetiMotorrit->setupEditAttributes();
        $this->mshitjeKapacitetiMotorrit->EditCustomAttributes = "";
        if (!$this->mshitjeKapacitetiMotorrit->Raw) {
            $this->mshitjeKapacitetiMotorrit->CurrentValue = HtmlDecode($this->mshitjeKapacitetiMotorrit->CurrentValue);
        }
        $this->mshitjeKapacitetiMotorrit->EditValue = $this->mshitjeKapacitetiMotorrit->CurrentValue;
        $this->mshitjeKapacitetiMotorrit->PlaceHolder = RemoveHtml($this->mshitjeKapacitetiMotorrit->caption());

        // mshitjeVitiProdhimit
        $this->mshitjeVitiProdhimit->setupEditAttributes();
        $this->mshitjeVitiProdhimit->EditCustomAttributes = "";
        $this->mshitjeVitiProdhimit->EditValue = $this->mshitjeVitiProdhimit->CurrentValue;
        $this->mshitjeVitiProdhimit->PlaceHolder = RemoveHtml($this->mshitjeVitiProdhimit->caption());
        if (strval($this->mshitjeVitiProdhimit->EditValue) != "" && is_numeric($this->mshitjeVitiProdhimit->EditValue)) {
            $this->mshitjeVitiProdhimit->EditValue = FormatNumber($this->mshitjeVitiProdhimit->EditValue, null);
        }

        // mshitjeKarburant
        $this->mshitjeKarburant->EditCustomAttributes = "";
        $this->mshitjeKarburant->EditValue = $this->mshitjeKarburant->options(false);
        $this->mshitjeKarburant->PlaceHolder = RemoveHtml($this->mshitjeKarburant->caption());

        // mshitjeNgjyra
        $this->mshitjeNgjyra->setupEditAttributes();
        $this->mshitjeNgjyra->EditCustomAttributes = "";
        if (!$this->mshitjeNgjyra->Raw) {
            $this->mshitjeNgjyra->CurrentValue = HtmlDecode($this->mshitjeNgjyra->CurrentValue);
        }
        $this->mshitjeNgjyra->EditValue = $this->mshitjeNgjyra->CurrentValue;
        $this->mshitjeNgjyra->PlaceHolder = RemoveHtml($this->mshitjeNgjyra->caption());

        // mshitjeNrVendeve
        $this->mshitjeNrVendeve->setupEditAttributes();
        $this->mshitjeNrVendeve->EditCustomAttributes = "";
        if (!$this->mshitjeNrVendeve->Raw) {
            $this->mshitjeNrVendeve->CurrentValue = HtmlDecode($this->mshitjeNrVendeve->CurrentValue);
        }
        $this->mshitjeNrVendeve->EditValue = $this->mshitjeNrVendeve->CurrentValue;
        $this->mshitjeNrVendeve->PlaceHolder = RemoveHtml($this->mshitjeNrVendeve->caption());

        // mshitjeKambio
        $this->mshitjeKambio->EditCustomAttributes = "";
        $this->mshitjeKambio->EditValue = $this->mshitjeKambio->options(false);
        $this->mshitjeKambio->PlaceHolder = RemoveHtml($this->mshitjeKambio->caption());

        // mshitjePrejardhja
        $this->mshitjePrejardhja->setupEditAttributes();
        $this->mshitjePrejardhja->EditCustomAttributes = "";
        if (!$this->mshitjePrejardhja->Raw) {
            $this->mshitjePrejardhja->CurrentValue = HtmlDecode($this->mshitjePrejardhja->CurrentValue);
        }
        $this->mshitjePrejardhja->EditValue = $this->mshitjePrejardhja->CurrentValue;
        $this->mshitjePrejardhja->PlaceHolder = RemoveHtml($this->mshitjePrejardhja->caption());

        // mshitjeTargaAL
        $this->mshitjeTargaAL->EditCustomAttributes = "";
        $this->mshitjeTargaAL->EditValue = $this->mshitjeTargaAL->options(false);
        $this->mshitjeTargaAL->PlaceHolder = RemoveHtml($this->mshitjeTargaAL->caption());

        // mshitjeKilometra
        $this->mshitjeKilometra->setupEditAttributes();
        $this->mshitjeKilometra->EditCustomAttributes = "";
        $this->mshitjeKilometra->EditValue = $this->mshitjeKilometra->CurrentValue;
        $this->mshitjeKilometra->PlaceHolder = RemoveHtml($this->mshitjeKilometra->caption());
        if (strval($this->mshitjeKilometra->EditValue) != "" && is_numeric($this->mshitjeKilometra->EditValue)) {
            $this->mshitjeKilometra->EditValue = FormatNumber($this->mshitjeKilometra->EditValue, null);
        }

        // mshitjeFotografi
        $this->mshitjeFotografi->setupEditAttributes();
        $this->mshitjeFotografi->EditCustomAttributes = "";
        $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
        if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
            $this->mshitjeFotografi->ImageWidth = 100;
            $this->mshitjeFotografi->ImageHeight = 0;
            $this->mshitjeFotografi->ImageAlt = $this->mshitjeFotografi->alt();
            $this->mshitjeFotografi->ImageCssClass = "ew-image";
            $this->mshitjeFotografi->EditValue = $this->mshitjeFotografi->Upload->DbValue;
        } else {
            $this->mshitjeFotografi->EditValue = "";
        }
        if (!EmptyValue($this->mshitjeFotografi->CurrentValue)) {
            $this->mshitjeFotografi->Upload->FileName = $this->mshitjeFotografi->CurrentValue;
        }

        // mshitjePershkrimi
        $this->mshitjePershkrimi->setupEditAttributes();
        $this->mshitjePershkrimi->EditCustomAttributes = "";
        $this->mshitjePershkrimi->EditValue = $this->mshitjePershkrimi->CurrentValue;
        $this->mshitjePershkrimi->PlaceHolder = RemoveHtml($this->mshitjePershkrimi->caption());

        // mshitjeCmimi
        $this->mshitjeCmimi->setupEditAttributes();
        $this->mshitjeCmimi->EditCustomAttributes = "";
        if (!$this->mshitjeCmimi->Raw) {
            $this->mshitjeCmimi->CurrentValue = HtmlDecode($this->mshitjeCmimi->CurrentValue);
        }
        $this->mshitjeCmimi->EditValue = $this->mshitjeCmimi->CurrentValue;
        $this->mshitjeCmimi->PlaceHolder = RemoveHtml($this->mshitjeCmimi->caption());

        // mshitjeIndex
        $this->mshitjeIndex->EditCustomAttributes = "";
        $this->mshitjeIndex->EditValue = $this->mshitjeIndex->options(false);
        $this->mshitjeIndex->PlaceHolder = RemoveHtml($this->mshitjeIndex->caption());

        // mshitjePromo
        $this->mshitjePromo->EditCustomAttributes = "";
        $this->mshitjePromo->EditValue = $this->mshitjePromo->options(false);
        $this->mshitjePromo->PlaceHolder = RemoveHtml($this->mshitjePromo->caption());

        // mshitjeAktiv
        $this->mshitjeAktiv->EditCustomAttributes = "";
        $this->mshitjeAktiv->EditValue = $this->mshitjeAktiv->options(false);
        $this->mshitjeAktiv->PlaceHolder = RemoveHtml($this->mshitjeAktiv->caption());

        // mshitjeShitur
        $this->mshitjeShitur->EditCustomAttributes = "";
        $this->mshitjeShitur->EditValue = $this->mshitjeShitur->options(false);
        $this->mshitjeShitur->PlaceHolder = RemoveHtml($this->mshitjeShitur->caption());

        // mshitjeAutori

        // mshitjeKrijuar

        // mshitjeAzhornuar

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->mshitjeID);
                    $doc->exportCaption($this->mshitjeMarka);
                    $doc->exportCaption($this->mshitjeModeli);
                    $doc->exportCaption($this->mshitjeTipi);
                    $doc->exportCaption($this->mshitjeStruktura);
                    $doc->exportCaption($this->mshitjeKapacitetiMotorrit);
                    $doc->exportCaption($this->mshitjeVitiProdhimit);
                    $doc->exportCaption($this->mshitjeKarburant);
                    $doc->exportCaption($this->mshitjeNgjyra);
                    $doc->exportCaption($this->mshitjeNrVendeve);
                    $doc->exportCaption($this->mshitjeKambio);
                    $doc->exportCaption($this->mshitjePrejardhja);
                    $doc->exportCaption($this->mshitjeTargaAL);
                    $doc->exportCaption($this->mshitjeKilometra);
                    $doc->exportCaption($this->mshitjeFotografi);
                    $doc->exportCaption($this->mshitjePershkrimi);
                    $doc->exportCaption($this->mshitjeCmimi);
                    $doc->exportCaption($this->mshitjeIndex);
                    $doc->exportCaption($this->mshitjePromo);
                    $doc->exportCaption($this->mshitjeAktiv);
                    $doc->exportCaption($this->mshitjeShitur);
                    $doc->exportCaption($this->mshitjeAutori);
                    $doc->exportCaption($this->mshitjeKrijuar);
                    $doc->exportCaption($this->mshitjeAzhornuar);
                } else {
                    $doc->exportCaption($this->mshitjeID);
                    $doc->exportCaption($this->mshitjeMarka);
                    $doc->exportCaption($this->mshitjeModeli);
                    $doc->exportCaption($this->mshitjeTipi);
                    $doc->exportCaption($this->mshitjeStruktura);
                    $doc->exportCaption($this->mshitjeKapacitetiMotorrit);
                    $doc->exportCaption($this->mshitjeVitiProdhimit);
                    $doc->exportCaption($this->mshitjeKarburant);
                    $doc->exportCaption($this->mshitjeNgjyra);
                    $doc->exportCaption($this->mshitjeNrVendeve);
                    $doc->exportCaption($this->mshitjeKambio);
                    $doc->exportCaption($this->mshitjePrejardhja);
                    $doc->exportCaption($this->mshitjeTargaAL);
                    $doc->exportCaption($this->mshitjeKilometra);
                    $doc->exportCaption($this->mshitjeCmimi);
                    $doc->exportCaption($this->mshitjeIndex);
                    $doc->exportCaption($this->mshitjePromo);
                    $doc->exportCaption($this->mshitjeAktiv);
                    $doc->exportCaption($this->mshitjeShitur);
                    $doc->exportCaption($this->mshitjeAutori);
                    $doc->exportCaption($this->mshitjeKrijuar);
                    $doc->exportCaption($this->mshitjeAzhornuar);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->mshitjeID);
                        $doc->exportField($this->mshitjeMarka);
                        $doc->exportField($this->mshitjeModeli);
                        $doc->exportField($this->mshitjeTipi);
                        $doc->exportField($this->mshitjeStruktura);
                        $doc->exportField($this->mshitjeKapacitetiMotorrit);
                        $doc->exportField($this->mshitjeVitiProdhimit);
                        $doc->exportField($this->mshitjeKarburant);
                        $doc->exportField($this->mshitjeNgjyra);
                        $doc->exportField($this->mshitjeNrVendeve);
                        $doc->exportField($this->mshitjeKambio);
                        $doc->exportField($this->mshitjePrejardhja);
                        $doc->exportField($this->mshitjeTargaAL);
                        $doc->exportField($this->mshitjeKilometra);
                        $doc->exportField($this->mshitjeFotografi);
                        $doc->exportField($this->mshitjePershkrimi);
                        $doc->exportField($this->mshitjeCmimi);
                        $doc->exportField($this->mshitjeIndex);
                        $doc->exportField($this->mshitjePromo);
                        $doc->exportField($this->mshitjeAktiv);
                        $doc->exportField($this->mshitjeShitur);
                        $doc->exportField($this->mshitjeAutori);
                        $doc->exportField($this->mshitjeKrijuar);
                        $doc->exportField($this->mshitjeAzhornuar);
                    } else {
                        $doc->exportField($this->mshitjeID);
                        $doc->exportField($this->mshitjeMarka);
                        $doc->exportField($this->mshitjeModeli);
                        $doc->exportField($this->mshitjeTipi);
                        $doc->exportField($this->mshitjeStruktura);
                        $doc->exportField($this->mshitjeKapacitetiMotorrit);
                        $doc->exportField($this->mshitjeVitiProdhimit);
                        $doc->exportField($this->mshitjeKarburant);
                        $doc->exportField($this->mshitjeNgjyra);
                        $doc->exportField($this->mshitjeNrVendeve);
                        $doc->exportField($this->mshitjeKambio);
                        $doc->exportField($this->mshitjePrejardhja);
                        $doc->exportField($this->mshitjeTargaAL);
                        $doc->exportField($this->mshitjeKilometra);
                        $doc->exportField($this->mshitjeCmimi);
                        $doc->exportField($this->mshitjeIndex);
                        $doc->exportField($this->mshitjePromo);
                        $doc->exportField($this->mshitjeAktiv);
                        $doc->exportField($this->mshitjeShitur);
                        $doc->exportField($this->mshitjeAutori);
                        $doc->exportField($this->mshitjeKrijuar);
                        $doc->exportField($this->mshitjeAzhornuar);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'mshitjeFotografi') {
            $fldName = "mshitjeFotografi";
            $fileNameFld = "mshitjeFotografi";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->mshitjeID->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssociative($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower(@$pathinfo["extension"]);
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
