<?php

/*
 * Generated from resources/grammar/Protobuf3.g4 by ANTLR 4.13.1
 */

namespace Prototype\Compiler\Internal\Parser {
	use Antlr\Antlr4\Runtime\Atn\ATN;
	use Antlr\Antlr4\Runtime\Atn\ATNDeserializer;
	use Antlr\Antlr4\Runtime\Atn\ParserATNSimulator;
	use Antlr\Antlr4\Runtime\Dfa\DFA;
	use Antlr\Antlr4\Runtime\Error\Exceptions\FailedPredicateException;
	use Antlr\Antlr4\Runtime\Error\Exceptions\NoViableAltException;
	use Antlr\Antlr4\Runtime\PredictionContexts\PredictionContextCache;
	use Antlr\Antlr4\Runtime\Error\Exceptions\RecognitionException;
	use Antlr\Antlr4\Runtime\RuleContext;
	use Antlr\Antlr4\Runtime\Token;
	use Antlr\Antlr4\Runtime\TokenStream;
	use Antlr\Antlr4\Runtime\Vocabulary;
	use Antlr\Antlr4\Runtime\VocabularyImpl;
	use Antlr\Antlr4\Runtime\RuntimeMetaData;
	use Antlr\Antlr4\Runtime\Parser;

	final class Protobuf3Parser extends Parser
	{
		public const SYNTAX = 1, IMPORT = 2, WEAK = 3, PUBLIC = 4, PACKAGE = 5, 
               OPTION = 6, OPTIONAL = 7, REPEATED = 8, ONEOF = 9, MAP = 10, 
               INT32 = 11, INT64 = 12, UINT32 = 13, UINT64 = 14, SINT32 = 15, 
               SINT64 = 16, FIXED32 = 17, FIXED64 = 18, SFIXED32 = 19, SFIXED64 = 20, 
               BOOL = 21, STRING = 22, DOUBLE = 23, FLOAT = 24, BYTES = 25, 
               RESERVED = 26, TO = 27, MAX = 28, ENUM = 29, MESSAGE = 30, 
               SERVICE = 31, EXTEND = 32, RPC = 33, STREAM = 34, RETURNS = 35, 
               PROTO3_LIT_SINGLE = 36, PROTO3_LIT_DOBULE = 37, SEMI = 38, 
               EQ = 39, LP = 40, RP = 41, LB = 42, RB = 43, LC = 44, RC = 45, 
               LT = 46, GT = 47, DOT = 48, COMMA = 49, COLON = 50, PLUS = 51, 
               MINUS = 52, STR_LIT = 53, BOOL_LIT = 54, FLOAT_LIT = 55, 
               INT_LIT = 56, IDENTIFIER = 57, WS = 58, LINE_COMMENT = 59, 
               COMMENT = 60;

		public const RULE_proto = 0, RULE_syntax = 1, RULE_importStatement = 2, 
               RULE_packageStatement = 3, RULE_optionStatement = 4, RULE_optionName = 5, 
               RULE_fieldLabel = 6, RULE_field = 7, RULE_fieldOptions = 8, 
               RULE_fieldOption = 9, RULE_fieldNumber = 10, RULE_oneof = 11, 
               RULE_oneofField = 12, RULE_mapField = 13, RULE_keyType = 14, 
               RULE_type_ = 15, RULE_reserved = 16, RULE_ranges = 17, RULE_range_ = 18, 
               RULE_reservedFieldNames = 19, RULE_topLevelDef = 20, RULE_enumDef = 21, 
               RULE_enumBody = 22, RULE_enumElement = 23, RULE_enumField = 24, 
               RULE_enumValueOptions = 25, RULE_enumValueOption = 26, RULE_messageDef = 27, 
               RULE_messageBody = 28, RULE_messageElement = 29, RULE_extendDef = 30, 
               RULE_serviceDef = 31, RULE_serviceElement = 32, RULE_rpc = 33, 
               RULE_constant = 34, RULE_blockLit = 35, RULE_emptyStatement_ = 36, 
               RULE_ident = 37, RULE_fullIdent = 38, RULE_messageName = 39, 
               RULE_enumName = 40, RULE_fieldName = 41, RULE_oneofName = 42, 
               RULE_mapName = 43, RULE_serviceName = 44, RULE_rpcName = 45, 
               RULE_messageType = 46, RULE_enumType = 47, RULE_intLit = 48, 
               RULE_strLit = 49, RULE_boolLit = 50, RULE_floatLit = 51, 
               RULE_keywords = 52;

		/**
		 * @var array<string>
		 */
		public const RULE_NAMES = [
			'proto', 'syntax', 'importStatement', 'packageStatement', 'optionStatement', 
			'optionName', 'fieldLabel', 'field', 'fieldOptions', 'fieldOption', 'fieldNumber', 
			'oneof', 'oneofField', 'mapField', 'keyType', 'type_', 'reserved', 'ranges', 
			'range_', 'reservedFieldNames', 'topLevelDef', 'enumDef', 'enumBody', 
			'enumElement', 'enumField', 'enumValueOptions', 'enumValueOption', 'messageDef', 
			'messageBody', 'messageElement', 'extendDef', 'serviceDef', 'serviceElement', 
			'rpc', 'constant', 'blockLit', 'emptyStatement_', 'ident', 'fullIdent', 
			'messageName', 'enumName', 'fieldName', 'oneofName', 'mapName', 'serviceName', 
			'rpcName', 'messageType', 'enumType', 'intLit', 'strLit', 'boolLit', 
			'floatLit', 'keywords'
		];

		/**
		 * @var array<string|null>
		 */
		private const LITERAL_NAMES = [
		    null, "'syntax'", "'import'", "'weak'", "'public'", "'package'", "'option'", 
		    "'optional'", "'repeated'", "'oneof'", "'map'", "'int32'", "'int64'", 
		    "'uint32'", "'uint64'", "'sint32'", "'sint64'", "'fixed32'", "'fixed64'", 
		    "'sfixed32'", "'sfixed64'", "'bool'", "'string'", "'double'", "'float'", 
		    "'bytes'", "'reserved'", "'to'", "'max'", "'enum'", "'message'", "'service'", 
		    "'extend'", "'rpc'", "'stream'", "'returns'", "'\"proto3\"'", "''proto3''", 
		    "';'", "'='", "'('", "')'", "'['", "']'", "'{'", "'}'", "'<'", "'>'", 
		    "'.'", "','", "':'", "'+'", "'-'"
		];

		/**
		 * @var array<string>
		 */
		private const SYMBOLIC_NAMES = [
		    null, "SYNTAX", "IMPORT", "WEAK", "PUBLIC", "PACKAGE", "OPTION", "OPTIONAL", 
		    "REPEATED", "ONEOF", "MAP", "INT32", "INT64", "UINT32", "UINT64", 
		    "SINT32", "SINT64", "FIXED32", "FIXED64", "SFIXED32", "SFIXED64", 
		    "BOOL", "STRING", "DOUBLE", "FLOAT", "BYTES", "RESERVED", "TO", "MAX", 
		    "ENUM", "MESSAGE", "SERVICE", "EXTEND", "RPC", "STREAM", "RETURNS", 
		    "PROTO3_LIT_SINGLE", "PROTO3_LIT_DOBULE", "SEMI", "EQ", "LP", "RP", 
		    "LB", "RB", "LC", "RC", "LT", "GT", "DOT", "COMMA", "COLON", "PLUS", 
		    "MINUS", "STR_LIT", "BOOL_LIT", "FLOAT_LIT", "INT_LIT", "IDENTIFIER", 
		    "WS", "LINE_COMMENT", "COMMENT"
		];

		private const SERIALIZED_ATN =
			[4, 1, 60, 496, 2, 0, 7, 0, 2, 1, 7, 1, 2, 2, 7, 2, 2, 3, 7, 3, 2, 4, 
		    7, 4, 2, 5, 7, 5, 2, 6, 7, 6, 2, 7, 7, 7, 2, 8, 7, 8, 2, 9, 7, 9, 
		    2, 10, 7, 10, 2, 11, 7, 11, 2, 12, 7, 12, 2, 13, 7, 13, 2, 14, 7, 
		    14, 2, 15, 7, 15, 2, 16, 7, 16, 2, 17, 7, 17, 2, 18, 7, 18, 2, 19, 
		    7, 19, 2, 20, 7, 20, 2, 21, 7, 21, 2, 22, 7, 22, 2, 23, 7, 23, 2, 
		    24, 7, 24, 2, 25, 7, 25, 2, 26, 7, 26, 2, 27, 7, 27, 2, 28, 7, 28, 
		    2, 29, 7, 29, 2, 30, 7, 30, 2, 31, 7, 31, 2, 32, 7, 32, 2, 33, 7, 
		    33, 2, 34, 7, 34, 2, 35, 7, 35, 2, 36, 7, 36, 2, 37, 7, 37, 2, 38, 
		    7, 38, 2, 39, 7, 39, 2, 40, 7, 40, 2, 41, 7, 41, 2, 42, 7, 42, 2, 
		    43, 7, 43, 2, 44, 7, 44, 2, 45, 7, 45, 2, 46, 7, 46, 2, 47, 7, 47, 
		    2, 48, 7, 48, 2, 49, 7, 49, 2, 50, 7, 50, 2, 51, 7, 51, 2, 52, 7, 
		    52, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 5, 0, 113, 8, 0, 10, 0, 12, 
		    0, 116, 9, 0, 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 1, 2, 
		    3, 2, 127, 8, 2, 1, 2, 1, 2, 1, 2, 1, 3, 1, 3, 1, 3, 1, 3, 1, 4, 1, 
		    4, 1, 4, 1, 4, 1, 4, 1, 4, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 3, 
		    5, 148, 8, 5, 3, 5, 150, 8, 5, 1, 6, 1, 6, 1, 7, 3, 7, 155, 8, 7, 
		    1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 3, 7, 165, 8, 7, 1, 
		    7, 1, 7, 1, 8, 1, 8, 1, 8, 5, 8, 172, 8, 8, 10, 8, 12, 8, 175, 9, 
		    8, 1, 9, 1, 9, 1, 9, 1, 9, 1, 10, 1, 10, 1, 11, 1, 11, 1, 11, 1, 11, 
		    1, 11, 1, 11, 5, 11, 189, 8, 11, 10, 11, 12, 11, 192, 9, 11, 1, 11, 
		    1, 11, 1, 12, 1, 12, 1, 12, 1, 12, 1, 12, 1, 12, 1, 12, 1, 12, 3, 
		    12, 204, 8, 12, 1, 12, 1, 12, 1, 13, 1, 13, 1, 13, 1, 13, 1, 13, 1, 
		    13, 1, 13, 1, 13, 1, 13, 1, 13, 1, 13, 1, 13, 1, 13, 3, 13, 221, 8, 
		    13, 1, 13, 1, 13, 1, 14, 1, 14, 1, 15, 1, 15, 1, 15, 1, 15, 1, 15, 
		    1, 15, 1, 15, 1, 15, 1, 15, 1, 15, 1, 15, 1, 15, 1, 15, 1, 15, 1, 
		    15, 1, 15, 1, 15, 3, 15, 244, 8, 15, 1, 16, 1, 16, 1, 16, 3, 16, 249, 
		    8, 16, 1, 16, 1, 16, 1, 17, 1, 17, 1, 17, 5, 17, 256, 8, 17, 10, 17, 
		    12, 17, 259, 9, 17, 1, 18, 1, 18, 1, 18, 1, 18, 3, 18, 265, 8, 18, 
		    3, 18, 267, 8, 18, 1, 19, 1, 19, 1, 19, 5, 19, 272, 8, 19, 10, 19, 
		    12, 19, 275, 9, 19, 1, 20, 1, 20, 1, 20, 1, 20, 3, 20, 281, 8, 20, 
		    1, 21, 1, 21, 1, 21, 1, 21, 1, 22, 1, 22, 5, 22, 289, 8, 22, 10, 22, 
		    12, 22, 292, 9, 22, 1, 22, 1, 22, 1, 23, 1, 23, 1, 23, 3, 23, 299, 
		    8, 23, 1, 24, 1, 24, 1, 24, 3, 24, 304, 8, 24, 1, 24, 1, 24, 3, 24, 
		    308, 8, 24, 1, 24, 1, 24, 1, 25, 1, 25, 1, 25, 1, 25, 5, 25, 316, 
		    8, 25, 10, 25, 12, 25, 319, 9, 25, 1, 25, 1, 25, 1, 26, 1, 26, 1, 
		    26, 1, 26, 1, 27, 1, 27, 1, 27, 1, 27, 1, 28, 1, 28, 5, 28, 333, 8, 
		    28, 10, 28, 12, 28, 336, 9, 28, 1, 28, 1, 28, 1, 29, 1, 29, 1, 29, 
		    1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 3, 29, 349, 8, 29, 1, 30, 
		    1, 30, 1, 30, 1, 30, 1, 30, 5, 30, 356, 8, 30, 10, 30, 12, 30, 359, 
		    9, 30, 1, 30, 1, 30, 1, 31, 1, 31, 1, 31, 1, 31, 5, 31, 367, 8, 31, 
		    10, 31, 12, 31, 370, 9, 31, 1, 31, 1, 31, 1, 32, 1, 32, 1, 32, 3, 
		    32, 377, 8, 32, 1, 33, 1, 33, 1, 33, 1, 33, 3, 33, 383, 8, 33, 1, 
		    33, 1, 33, 1, 33, 1, 33, 1, 33, 3, 33, 390, 8, 33, 1, 33, 1, 33, 1, 
		    33, 1, 33, 1, 33, 5, 33, 397, 8, 33, 10, 33, 12, 33, 400, 9, 33, 1, 
		    33, 1, 33, 3, 33, 404, 8, 33, 1, 34, 1, 34, 3, 34, 408, 8, 34, 1, 
		    34, 1, 34, 3, 34, 412, 8, 34, 1, 34, 1, 34, 1, 34, 1, 34, 3, 34, 418, 
		    8, 34, 1, 35, 1, 35, 1, 35, 1, 35, 1, 35, 5, 35, 425, 8, 35, 10, 35, 
		    12, 35, 428, 9, 35, 1, 35, 1, 35, 1, 36, 1, 36, 1, 37, 1, 37, 3, 37, 
		    436, 8, 37, 1, 38, 1, 38, 1, 38, 5, 38, 441, 8, 38, 10, 38, 12, 38, 
		    444, 9, 38, 1, 39, 1, 39, 1, 40, 1, 40, 1, 41, 1, 41, 1, 42, 1, 42, 
		    1, 43, 1, 43, 1, 44, 1, 44, 1, 45, 1, 45, 1, 46, 3, 46, 461, 8, 46, 
		    1, 46, 1, 46, 1, 46, 5, 46, 466, 8, 46, 10, 46, 12, 46, 469, 9, 46, 
		    1, 46, 1, 46, 1, 47, 3, 47, 474, 8, 47, 1, 47, 1, 47, 1, 47, 5, 47, 
		    479, 8, 47, 10, 47, 12, 47, 482, 9, 47, 1, 47, 1, 47, 1, 48, 1, 48, 
		    1, 49, 1, 49, 1, 50, 1, 50, 1, 51, 1, 51, 1, 52, 1, 52, 1, 52, 0, 
		    0, 53, 0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 
		    32, 34, 36, 38, 40, 42, 44, 46, 48, 50, 52, 54, 56, 58, 60, 62, 64, 
		    66, 68, 70, 72, 74, 76, 78, 80, 82, 84, 86, 88, 90, 92, 94, 96, 98, 
		    100, 102, 104, 0, 7, 1, 0, 36, 37, 1, 0, 3, 4, 1, 0, 7, 8, 1, 0, 11, 
		    22, 1, 0, 51, 52, 2, 0, 36, 37, 53, 53, 2, 0, 1, 35, 54, 54, 521, 
		    0, 106, 1, 0, 0, 0, 2, 119, 1, 0, 0, 0, 4, 124, 1, 0, 0, 0, 6, 131, 
		    1, 0, 0, 0, 8, 135, 1, 0, 0, 0, 10, 149, 1, 0, 0, 0, 12, 151, 1, 0, 
		    0, 0, 14, 154, 1, 0, 0, 0, 16, 168, 1, 0, 0, 0, 18, 176, 1, 0, 0, 
		    0, 20, 180, 1, 0, 0, 0, 22, 182, 1, 0, 0, 0, 24, 195, 1, 0, 0, 0, 
		    26, 207, 1, 0, 0, 0, 28, 224, 1, 0, 0, 0, 30, 243, 1, 0, 0, 0, 32, 
		    245, 1, 0, 0, 0, 34, 252, 1, 0, 0, 0, 36, 260, 1, 0, 0, 0, 38, 268, 
		    1, 0, 0, 0, 40, 280, 1, 0, 0, 0, 42, 282, 1, 0, 0, 0, 44, 286, 1, 
		    0, 0, 0, 46, 298, 1, 0, 0, 0, 48, 300, 1, 0, 0, 0, 50, 311, 1, 0, 
		    0, 0, 52, 322, 1, 0, 0, 0, 54, 326, 1, 0, 0, 0, 56, 330, 1, 0, 0, 
		    0, 58, 348, 1, 0, 0, 0, 60, 350, 1, 0, 0, 0, 62, 362, 1, 0, 0, 0, 
		    64, 376, 1, 0, 0, 0, 66, 378, 1, 0, 0, 0, 68, 417, 1, 0, 0, 0, 70, 
		    419, 1, 0, 0, 0, 72, 431, 1, 0, 0, 0, 74, 435, 1, 0, 0, 0, 76, 437, 
		    1, 0, 0, 0, 78, 445, 1, 0, 0, 0, 80, 447, 1, 0, 0, 0, 82, 449, 1, 
		    0, 0, 0, 84, 451, 1, 0, 0, 0, 86, 453, 1, 0, 0, 0, 88, 455, 1, 0, 
		    0, 0, 90, 457, 1, 0, 0, 0, 92, 460, 1, 0, 0, 0, 94, 473, 1, 0, 0, 
		    0, 96, 485, 1, 0, 0, 0, 98, 487, 1, 0, 0, 0, 100, 489, 1, 0, 0, 0, 
		    102, 491, 1, 0, 0, 0, 104, 493, 1, 0, 0, 0, 106, 114, 3, 2, 1, 0, 
		    107, 113, 3, 4, 2, 0, 108, 113, 3, 6, 3, 0, 109, 113, 3, 8, 4, 0, 
		    110, 113, 3, 40, 20, 0, 111, 113, 3, 72, 36, 0, 112, 107, 1, 0, 0, 
		    0, 112, 108, 1, 0, 0, 0, 112, 109, 1, 0, 0, 0, 112, 110, 1, 0, 0, 
		    0, 112, 111, 1, 0, 0, 0, 113, 116, 1, 0, 0, 0, 114, 112, 1, 0, 0, 
		    0, 114, 115, 1, 0, 0, 0, 115, 117, 1, 0, 0, 0, 116, 114, 1, 0, 0, 
		    0, 117, 118, 5, 0, 0, 1, 118, 1, 1, 0, 0, 0, 119, 120, 5, 1, 0, 0, 
		    120, 121, 5, 39, 0, 0, 121, 122, 7, 0, 0, 0, 122, 123, 5, 38, 0, 0, 
		    123, 3, 1, 0, 0, 0, 124, 126, 5, 2, 0, 0, 125, 127, 7, 1, 0, 0, 126, 
		    125, 1, 0, 0, 0, 126, 127, 1, 0, 0, 0, 127, 128, 1, 0, 0, 0, 128, 
		    129, 3, 98, 49, 0, 129, 130, 5, 38, 0, 0, 130, 5, 1, 0, 0, 0, 131, 
		    132, 5, 5, 0, 0, 132, 133, 3, 76, 38, 0, 133, 134, 5, 38, 0, 0, 134, 
		    7, 1, 0, 0, 0, 135, 136, 5, 6, 0, 0, 136, 137, 3, 10, 5, 0, 137, 138, 
		    5, 39, 0, 0, 138, 139, 3, 68, 34, 0, 139, 140, 5, 38, 0, 0, 140, 9, 
		    1, 0, 0, 0, 141, 150, 3, 76, 38, 0, 142, 143, 5, 40, 0, 0, 143, 144, 
		    3, 76, 38, 0, 144, 147, 5, 41, 0, 0, 145, 146, 5, 48, 0, 0, 146, 148, 
		    3, 76, 38, 0, 147, 145, 1, 0, 0, 0, 147, 148, 1, 0, 0, 0, 148, 150, 
		    1, 0, 0, 0, 149, 141, 1, 0, 0, 0, 149, 142, 1, 0, 0, 0, 150, 11, 1, 
		    0, 0, 0, 151, 152, 7, 2, 0, 0, 152, 13, 1, 0, 0, 0, 153, 155, 3, 12, 
		    6, 0, 154, 153, 1, 0, 0, 0, 154, 155, 1, 0, 0, 0, 155, 156, 1, 0, 
		    0, 0, 156, 157, 3, 30, 15, 0, 157, 158, 3, 82, 41, 0, 158, 159, 5, 
		    39, 0, 0, 159, 164, 3, 20, 10, 0, 160, 161, 5, 42, 0, 0, 161, 162, 
		    3, 16, 8, 0, 162, 163, 5, 43, 0, 0, 163, 165, 1, 0, 0, 0, 164, 160, 
		    1, 0, 0, 0, 164, 165, 1, 0, 0, 0, 165, 166, 1, 0, 0, 0, 166, 167, 
		    5, 38, 0, 0, 167, 15, 1, 0, 0, 0, 168, 173, 3, 18, 9, 0, 169, 170, 
		    5, 49, 0, 0, 170, 172, 3, 18, 9, 0, 171, 169, 1, 0, 0, 0, 172, 175, 
		    1, 0, 0, 0, 173, 171, 1, 0, 0, 0, 173, 174, 1, 0, 0, 0, 174, 17, 1, 
		    0, 0, 0, 175, 173, 1, 0, 0, 0, 176, 177, 3, 10, 5, 0, 177, 178, 5, 
		    39, 0, 0, 178, 179, 3, 68, 34, 0, 179, 19, 1, 0, 0, 0, 180, 181, 3, 
		    96, 48, 0, 181, 21, 1, 0, 0, 0, 182, 183, 5, 9, 0, 0, 183, 184, 3, 
		    84, 42, 0, 184, 190, 5, 44, 0, 0, 185, 189, 3, 8, 4, 0, 186, 189, 
		    3, 24, 12, 0, 187, 189, 3, 72, 36, 0, 188, 185, 1, 0, 0, 0, 188, 186, 
		    1, 0, 0, 0, 188, 187, 1, 0, 0, 0, 189, 192, 1, 0, 0, 0, 190, 188, 
		    1, 0, 0, 0, 190, 191, 1, 0, 0, 0, 191, 193, 1, 0, 0, 0, 192, 190, 
		    1, 0, 0, 0, 193, 194, 5, 45, 0, 0, 194, 23, 1, 0, 0, 0, 195, 196, 
		    3, 30, 15, 0, 196, 197, 3, 82, 41, 0, 197, 198, 5, 39, 0, 0, 198, 
		    203, 3, 20, 10, 0, 199, 200, 5, 42, 0, 0, 200, 201, 3, 16, 8, 0, 201, 
		    202, 5, 43, 0, 0, 202, 204, 1, 0, 0, 0, 203, 199, 1, 0, 0, 0, 203, 
		    204, 1, 0, 0, 0, 204, 205, 1, 0, 0, 0, 205, 206, 5, 38, 0, 0, 206, 
		    25, 1, 0, 0, 0, 207, 208, 5, 10, 0, 0, 208, 209, 5, 46, 0, 0, 209, 
		    210, 3, 28, 14, 0, 210, 211, 5, 49, 0, 0, 211, 212, 3, 30, 15, 0, 
		    212, 213, 5, 47, 0, 0, 213, 214, 3, 86, 43, 0, 214, 215, 5, 39, 0, 
		    0, 215, 220, 3, 20, 10, 0, 216, 217, 5, 42, 0, 0, 217, 218, 3, 16, 
		    8, 0, 218, 219, 5, 43, 0, 0, 219, 221, 1, 0, 0, 0, 220, 216, 1, 0, 
		    0, 0, 220, 221, 1, 0, 0, 0, 221, 222, 1, 0, 0, 0, 222, 223, 5, 38, 
		    0, 0, 223, 27, 1, 0, 0, 0, 224, 225, 7, 3, 0, 0, 225, 29, 1, 0, 0, 
		    0, 226, 244, 5, 23, 0, 0, 227, 244, 5, 24, 0, 0, 228, 244, 5, 11, 
		    0, 0, 229, 244, 5, 12, 0, 0, 230, 244, 5, 13, 0, 0, 231, 244, 5, 14, 
		    0, 0, 232, 244, 5, 15, 0, 0, 233, 244, 5, 16, 0, 0, 234, 244, 5, 17, 
		    0, 0, 235, 244, 5, 18, 0, 0, 236, 244, 5, 19, 0, 0, 237, 244, 5, 20, 
		    0, 0, 238, 244, 5, 21, 0, 0, 239, 244, 5, 22, 0, 0, 240, 244, 5, 25, 
		    0, 0, 241, 244, 3, 92, 46, 0, 242, 244, 3, 94, 47, 0, 243, 226, 1, 
		    0, 0, 0, 243, 227, 1, 0, 0, 0, 243, 228, 1, 0, 0, 0, 243, 229, 1, 
		    0, 0, 0, 243, 230, 1, 0, 0, 0, 243, 231, 1, 0, 0, 0, 243, 232, 1, 
		    0, 0, 0, 243, 233, 1, 0, 0, 0, 243, 234, 1, 0, 0, 0, 243, 235, 1, 
		    0, 0, 0, 243, 236, 1, 0, 0, 0, 243, 237, 1, 0, 0, 0, 243, 238, 1, 
		    0, 0, 0, 243, 239, 1, 0, 0, 0, 243, 240, 1, 0, 0, 0, 243, 241, 1, 
		    0, 0, 0, 243, 242, 1, 0, 0, 0, 244, 31, 1, 0, 0, 0, 245, 248, 5, 26, 
		    0, 0, 246, 249, 3, 34, 17, 0, 247, 249, 3, 38, 19, 0, 248, 246, 1, 
		    0, 0, 0, 248, 247, 1, 0, 0, 0, 249, 250, 1, 0, 0, 0, 250, 251, 5, 
		    38, 0, 0, 251, 33, 1, 0, 0, 0, 252, 257, 3, 36, 18, 0, 253, 254, 5, 
		    49, 0, 0, 254, 256, 3, 36, 18, 0, 255, 253, 1, 0, 0, 0, 256, 259, 
		    1, 0, 0, 0, 257, 255, 1, 0, 0, 0, 257, 258, 1, 0, 0, 0, 258, 35, 1, 
		    0, 0, 0, 259, 257, 1, 0, 0, 0, 260, 266, 3, 96, 48, 0, 261, 264, 5, 
		    27, 0, 0, 262, 265, 3, 96, 48, 0, 263, 265, 5, 28, 0, 0, 264, 262, 
		    1, 0, 0, 0, 264, 263, 1, 0, 0, 0, 265, 267, 1, 0, 0, 0, 266, 261, 
		    1, 0, 0, 0, 266, 267, 1, 0, 0, 0, 267, 37, 1, 0, 0, 0, 268, 273, 3, 
		    98, 49, 0, 269, 270, 5, 49, 0, 0, 270, 272, 3, 98, 49, 0, 271, 269, 
		    1, 0, 0, 0, 272, 275, 1, 0, 0, 0, 273, 271, 1, 0, 0, 0, 273, 274, 
		    1, 0, 0, 0, 274, 39, 1, 0, 0, 0, 275, 273, 1, 0, 0, 0, 276, 281, 3, 
		    54, 27, 0, 277, 281, 3, 42, 21, 0, 278, 281, 3, 60, 30, 0, 279, 281, 
		    3, 62, 31, 0, 280, 276, 1, 0, 0, 0, 280, 277, 1, 0, 0, 0, 280, 278, 
		    1, 0, 0, 0, 280, 279, 1, 0, 0, 0, 281, 41, 1, 0, 0, 0, 282, 283, 5, 
		    29, 0, 0, 283, 284, 3, 80, 40, 0, 284, 285, 3, 44, 22, 0, 285, 43, 
		    1, 0, 0, 0, 286, 290, 5, 44, 0, 0, 287, 289, 3, 46, 23, 0, 288, 287, 
		    1, 0, 0, 0, 289, 292, 1, 0, 0, 0, 290, 288, 1, 0, 0, 0, 290, 291, 
		    1, 0, 0, 0, 291, 293, 1, 0, 0, 0, 292, 290, 1, 0, 0, 0, 293, 294, 
		    5, 45, 0, 0, 294, 45, 1, 0, 0, 0, 295, 299, 3, 8, 4, 0, 296, 299, 
		    3, 48, 24, 0, 297, 299, 3, 72, 36, 0, 298, 295, 1, 0, 0, 0, 298, 296, 
		    1, 0, 0, 0, 298, 297, 1, 0, 0, 0, 299, 47, 1, 0, 0, 0, 300, 301, 3, 
		    74, 37, 0, 301, 303, 5, 39, 0, 0, 302, 304, 5, 52, 0, 0, 303, 302, 
		    1, 0, 0, 0, 303, 304, 1, 0, 0, 0, 304, 305, 1, 0, 0, 0, 305, 307, 
		    3, 96, 48, 0, 306, 308, 3, 50, 25, 0, 307, 306, 1, 0, 0, 0, 307, 308, 
		    1, 0, 0, 0, 308, 309, 1, 0, 0, 0, 309, 310, 5, 38, 0, 0, 310, 49, 
		    1, 0, 0, 0, 311, 312, 5, 42, 0, 0, 312, 317, 3, 52, 26, 0, 313, 314, 
		    5, 49, 0, 0, 314, 316, 3, 52, 26, 0, 315, 313, 1, 0, 0, 0, 316, 319, 
		    1, 0, 0, 0, 317, 315, 1, 0, 0, 0, 317, 318, 1, 0, 0, 0, 318, 320, 
		    1, 0, 0, 0, 319, 317, 1, 0, 0, 0, 320, 321, 5, 43, 0, 0, 321, 51, 
		    1, 0, 0, 0, 322, 323, 3, 10, 5, 0, 323, 324, 5, 39, 0, 0, 324, 325, 
		    3, 68, 34, 0, 325, 53, 1, 0, 0, 0, 326, 327, 5, 30, 0, 0, 327, 328, 
		    3, 78, 39, 0, 328, 329, 3, 56, 28, 0, 329, 55, 1, 0, 0, 0, 330, 334, 
		    5, 44, 0, 0, 331, 333, 3, 58, 29, 0, 332, 331, 1, 0, 0, 0, 333, 336, 
		    1, 0, 0, 0, 334, 332, 1, 0, 0, 0, 334, 335, 1, 0, 0, 0, 335, 337, 
		    1, 0, 0, 0, 336, 334, 1, 0, 0, 0, 337, 338, 5, 45, 0, 0, 338, 57, 
		    1, 0, 0, 0, 339, 349, 3, 14, 7, 0, 340, 349, 3, 42, 21, 0, 341, 349, 
		    3, 54, 27, 0, 342, 349, 3, 60, 30, 0, 343, 349, 3, 8, 4, 0, 344, 349, 
		    3, 22, 11, 0, 345, 349, 3, 26, 13, 0, 346, 349, 3, 32, 16, 0, 347, 
		    349, 3, 72, 36, 0, 348, 339, 1, 0, 0, 0, 348, 340, 1, 0, 0, 0, 348, 
		    341, 1, 0, 0, 0, 348, 342, 1, 0, 0, 0, 348, 343, 1, 0, 0, 0, 348, 
		    344, 1, 0, 0, 0, 348, 345, 1, 0, 0, 0, 348, 346, 1, 0, 0, 0, 348, 
		    347, 1, 0, 0, 0, 349, 59, 1, 0, 0, 0, 350, 351, 5, 32, 0, 0, 351, 
		    352, 3, 92, 46, 0, 352, 357, 5, 44, 0, 0, 353, 356, 3, 14, 7, 0, 354, 
		    356, 3, 72, 36, 0, 355, 353, 1, 0, 0, 0, 355, 354, 1, 0, 0, 0, 356, 
		    359, 1, 0, 0, 0, 357, 355, 1, 0, 0, 0, 357, 358, 1, 0, 0, 0, 358, 
		    360, 1, 0, 0, 0, 359, 357, 1, 0, 0, 0, 360, 361, 5, 45, 0, 0, 361, 
		    61, 1, 0, 0, 0, 362, 363, 5, 31, 0, 0, 363, 364, 3, 88, 44, 0, 364, 
		    368, 5, 44, 0, 0, 365, 367, 3, 64, 32, 0, 366, 365, 1, 0, 0, 0, 367, 
		    370, 1, 0, 0, 0, 368, 366, 1, 0, 0, 0, 368, 369, 1, 0, 0, 0, 369, 
		    371, 1, 0, 0, 0, 370, 368, 1, 0, 0, 0, 371, 372, 5, 45, 0, 0, 372, 
		    63, 1, 0, 0, 0, 373, 377, 3, 8, 4, 0, 374, 377, 3, 66, 33, 0, 375, 
		    377, 3, 72, 36, 0, 376, 373, 1, 0, 0, 0, 376, 374, 1, 0, 0, 0, 376, 
		    375, 1, 0, 0, 0, 377, 65, 1, 0, 0, 0, 378, 379, 5, 33, 0, 0, 379, 
		    380, 3, 90, 45, 0, 380, 382, 5, 40, 0, 0, 381, 383, 5, 34, 0, 0, 382, 
		    381, 1, 0, 0, 0, 382, 383, 1, 0, 0, 0, 383, 384, 1, 0, 0, 0, 384, 
		    385, 3, 92, 46, 0, 385, 386, 5, 41, 0, 0, 386, 387, 5, 35, 0, 0, 387, 
		    389, 5, 40, 0, 0, 388, 390, 5, 34, 0, 0, 389, 388, 1, 0, 0, 0, 389, 
		    390, 1, 0, 0, 0, 390, 391, 1, 0, 0, 0, 391, 392, 3, 92, 46, 0, 392, 
		    403, 5, 41, 0, 0, 393, 398, 5, 44, 0, 0, 394, 397, 3, 8, 4, 0, 395, 
		    397, 3, 72, 36, 0, 396, 394, 1, 0, 0, 0, 396, 395, 1, 0, 0, 0, 397, 
		    400, 1, 0, 0, 0, 398, 396, 1, 0, 0, 0, 398, 399, 1, 0, 0, 0, 399, 
		    401, 1, 0, 0, 0, 400, 398, 1, 0, 0, 0, 401, 404, 5, 45, 0, 0, 402, 
		    404, 5, 38, 0, 0, 403, 393, 1, 0, 0, 0, 403, 402, 1, 0, 0, 0, 404, 
		    67, 1, 0, 0, 0, 405, 418, 3, 76, 38, 0, 406, 408, 7, 4, 0, 0, 407, 
		    406, 1, 0, 0, 0, 407, 408, 1, 0, 0, 0, 408, 409, 1, 0, 0, 0, 409, 
		    418, 3, 96, 48, 0, 410, 412, 7, 4, 0, 0, 411, 410, 1, 0, 0, 0, 411, 
		    412, 1, 0, 0, 0, 412, 413, 1, 0, 0, 0, 413, 418, 3, 102, 51, 0, 414, 
		    418, 3, 98, 49, 0, 415, 418, 3, 100, 50, 0, 416, 418, 3, 70, 35, 0, 
		    417, 405, 1, 0, 0, 0, 417, 407, 1, 0, 0, 0, 417, 411, 1, 0, 0, 0, 
		    417, 414, 1, 0, 0, 0, 417, 415, 1, 0, 0, 0, 417, 416, 1, 0, 0, 0, 
		    418, 69, 1, 0, 0, 0, 419, 426, 5, 44, 0, 0, 420, 421, 3, 74, 37, 0, 
		    421, 422, 5, 50, 0, 0, 422, 423, 3, 68, 34, 0, 423, 425, 1, 0, 0, 
		    0, 424, 420, 1, 0, 0, 0, 425, 428, 1, 0, 0, 0, 426, 424, 1, 0, 0, 
		    0, 426, 427, 1, 0, 0, 0, 427, 429, 1, 0, 0, 0, 428, 426, 1, 0, 0, 
		    0, 429, 430, 5, 45, 0, 0, 430, 71, 1, 0, 0, 0, 431, 432, 5, 38, 0, 
		    0, 432, 73, 1, 0, 0, 0, 433, 436, 5, 57, 0, 0, 434, 436, 3, 104, 52, 
		    0, 435, 433, 1, 0, 0, 0, 435, 434, 1, 0, 0, 0, 436, 75, 1, 0, 0, 0, 
		    437, 442, 3, 74, 37, 0, 438, 439, 5, 48, 0, 0, 439, 441, 3, 74, 37, 
		    0, 440, 438, 1, 0, 0, 0, 441, 444, 1, 0, 0, 0, 442, 440, 1, 0, 0, 
		    0, 442, 443, 1, 0, 0, 0, 443, 77, 1, 0, 0, 0, 444, 442, 1, 0, 0, 0, 
		    445, 446, 3, 74, 37, 0, 446, 79, 1, 0, 0, 0, 447, 448, 3, 74, 37, 
		    0, 448, 81, 1, 0, 0, 0, 449, 450, 3, 74, 37, 0, 450, 83, 1, 0, 0, 
		    0, 451, 452, 3, 74, 37, 0, 452, 85, 1, 0, 0, 0, 453, 454, 3, 74, 37, 
		    0, 454, 87, 1, 0, 0, 0, 455, 456, 3, 74, 37, 0, 456, 89, 1, 0, 0, 
		    0, 457, 458, 3, 74, 37, 0, 458, 91, 1, 0, 0, 0, 459, 461, 5, 48, 0, 
		    0, 460, 459, 1, 0, 0, 0, 460, 461, 1, 0, 0, 0, 461, 467, 1, 0, 0, 
		    0, 462, 463, 3, 74, 37, 0, 463, 464, 5, 48, 0, 0, 464, 466, 1, 0, 
		    0, 0, 465, 462, 1, 0, 0, 0, 466, 469, 1, 0, 0, 0, 467, 465, 1, 0, 
		    0, 0, 467, 468, 1, 0, 0, 0, 468, 470, 1, 0, 0, 0, 469, 467, 1, 0, 
		    0, 0, 470, 471, 3, 78, 39, 0, 471, 93, 1, 0, 0, 0, 472, 474, 5, 48, 
		    0, 0, 473, 472, 1, 0, 0, 0, 473, 474, 1, 0, 0, 0, 474, 480, 1, 0, 
		    0, 0, 475, 476, 3, 74, 37, 0, 476, 477, 5, 48, 0, 0, 477, 479, 1, 
		    0, 0, 0, 478, 475, 1, 0, 0, 0, 479, 482, 1, 0, 0, 0, 480, 478, 1, 
		    0, 0, 0, 480, 481, 1, 0, 0, 0, 481, 483, 1, 0, 0, 0, 482, 480, 1, 
		    0, 0, 0, 483, 484, 3, 80, 40, 0, 484, 95, 1, 0, 0, 0, 485, 486, 5, 
		    56, 0, 0, 486, 97, 1, 0, 0, 0, 487, 488, 7, 5, 0, 0, 488, 99, 1, 0, 
		    0, 0, 489, 490, 5, 54, 0, 0, 490, 101, 1, 0, 0, 0, 491, 492, 5, 55, 
		    0, 0, 492, 103, 1, 0, 0, 0, 493, 494, 7, 6, 0, 0, 494, 105, 1, 0, 
		    0, 0, 45, 112, 114, 126, 147, 149, 154, 164, 173, 188, 190, 203, 220, 
		    243, 248, 257, 264, 266, 273, 280, 290, 298, 303, 307, 317, 334, 348, 
		    355, 357, 368, 376, 382, 389, 396, 398, 403, 407, 411, 417, 426, 435, 
		    442, 460, 467, 473, 480];
		protected static $atn;
		protected static $decisionToDFA;
		protected static $sharedContextCache;

		public function __construct(TokenStream $input)
		{
			parent::__construct($input);

			self::initialize();

			$this->interp = new ParserATNSimulator($this, self::$atn, self::$decisionToDFA, self::$sharedContextCache);
		}

		private static function initialize(): void
		{
			if (self::$atn !== null) {
				return;
			}

			RuntimeMetaData::checkVersion('4.13.1', RuntimeMetaData::VERSION);

			$atn = (new ATNDeserializer())->deserialize(self::SERIALIZED_ATN);

			$decisionToDFA = [];
			for ($i = 0, $count = $atn->getNumberOfDecisions(); $i < $count; $i++) {
				$decisionToDFA[] = new DFA($atn->getDecisionState($i), $i);
			}

			self::$atn = $atn;
			self::$decisionToDFA = $decisionToDFA;
			self::$sharedContextCache = new PredictionContextCache();
		}

		public function getGrammarFileName(): string
		{
			return "Protobuf3.g4";
		}

		public function getRuleNames(): array
		{
			return self::RULE_NAMES;
		}

		public function getSerializedATN(): array
		{
			return self::SERIALIZED_ATN;
		}

		public function getATN(): ATN
		{
			return self::$atn;
		}

		public function getVocabulary(): Vocabulary
        {
            static $vocabulary;

			return $vocabulary = $vocabulary ?? new VocabularyImpl(self::LITERAL_NAMES, self::SYMBOLIC_NAMES);
        }

		/**
		 * @throws RecognitionException
		 */
		public function proto(): Context\ProtoContext
		{
		    $localContext = new Context\ProtoContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 0, self::RULE_proto);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(106);
		        $this->syntax();
		        $this->setState(114);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 282930970724) !== 0)) {
		        	$this->setState(112);
		        	$this->errorHandler->sync($this);

		        	switch ($this->input->LA(1)) {
		        	    case self::IMPORT:
		        	    	$this->setState(107);
		        	    	$this->importStatement();
		        	    	break;

		        	    case self::PACKAGE:
		        	    	$this->setState(108);
		        	    	$this->packageStatement();
		        	    	break;

		        	    case self::OPTION:
		        	    	$this->setState(109);
		        	    	$this->optionStatement();
		        	    	break;

		        	    case self::ENUM:
		        	    case self::MESSAGE:
		        	    case self::SERVICE:
		        	    case self::EXTEND:
		        	    	$this->setState(110);
		        	    	$this->topLevelDef();
		        	    	break;

		        	    case self::SEMI:
		        	    	$this->setState(111);
		        	    	$this->emptyStatement_();
		        	    	break;

		        	default:
		        		throw new NoViableAltException($this);
		        	}
		        	$this->setState(116);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(117);
		        $this->match(self::EOF);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function syntax(): Context\SyntaxContext
		{
		    $localContext = new Context\SyntaxContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 2, self::RULE_syntax);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(119);
		        $this->match(self::SYNTAX);
		        $this->setState(120);
		        $this->match(self::EQ);
		        $this->setState(121);

		        $_la = $this->input->LA(1);

		        if (!($_la === self::PROTO3_LIT_SINGLE || $_la === self::PROTO3_LIT_DOBULE)) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		        $this->setState(122);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function importStatement(): Context\ImportStatementContext
		{
		    $localContext = new Context\ImportStatementContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 4, self::RULE_importStatement);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(124);
		        $this->match(self::IMPORT);
		        $this->setState(126);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::WEAK || $_la === self::PUBLIC) {
		        	$this->setState(125);

		        	$_la = $this->input->LA(1);

		        	if (!($_la === self::WEAK || $_la === self::PUBLIC)) {
		        	$this->errorHandler->recoverInline($this);
		        	} else {
		        		if ($this->input->LA(1) === Token::EOF) {
		        		    $this->matchedEOF = true;
		        	    }

		        		$this->errorHandler->reportMatch($this);
		        		$this->consume();
		        	}
		        }
		        $this->setState(128);
		        $this->strLit();
		        $this->setState(129);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function packageStatement(): Context\PackageStatementContext
		{
		    $localContext = new Context\PackageStatementContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 6, self::RULE_packageStatement);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(131);
		        $this->match(self::PACKAGE);
		        $this->setState(132);
		        $this->fullIdent();
		        $this->setState(133);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function optionStatement(): Context\OptionStatementContext
		{
		    $localContext = new Context\OptionStatementContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 8, self::RULE_optionStatement);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(135);
		        $this->match(self::OPTION);
		        $this->setState(136);
		        $this->optionName();
		        $this->setState(137);
		        $this->match(self::EQ);
		        $this->setState(138);
		        $this->constant();
		        $this->setState(139);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function optionName(): Context\OptionNameContext
		{
		    $localContext = new Context\OptionNameContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 10, self::RULE_optionName);

		    try {
		        $this->setState(149);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::SYNTAX:
		            case self::IMPORT:
		            case self::WEAK:
		            case self::PUBLIC:
		            case self::PACKAGE:
		            case self::OPTION:
		            case self::OPTIONAL:
		            case self::REPEATED:
		            case self::ONEOF:
		            case self::MAP:
		            case self::INT32:
		            case self::INT64:
		            case self::UINT32:
		            case self::UINT64:
		            case self::SINT32:
		            case self::SINT64:
		            case self::FIXED32:
		            case self::FIXED64:
		            case self::SFIXED32:
		            case self::SFIXED64:
		            case self::BOOL:
		            case self::STRING:
		            case self::DOUBLE:
		            case self::FLOAT:
		            case self::BYTES:
		            case self::RESERVED:
		            case self::TO:
		            case self::MAX:
		            case self::ENUM:
		            case self::MESSAGE:
		            case self::SERVICE:
		            case self::EXTEND:
		            case self::RPC:
		            case self::STREAM:
		            case self::RETURNS:
		            case self::BOOL_LIT:
		            case self::IDENTIFIER:
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(141);
		            	$this->fullIdent();
		            	break;

		            case self::LP:
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(142);
		            	$this->match(self::LP);
		            	$this->setState(143);
		            	$this->fullIdent();
		            	$this->setState(144);
		            	$this->match(self::RP);
		            	$this->setState(147);
		            	$this->errorHandler->sync($this);
		            	$_la = $this->input->LA(1);

		            	if ($_la === self::DOT) {
		            		$this->setState(145);
		            		$this->match(self::DOT);
		            		$this->setState(146);
		            		$this->fullIdent();
		            	}
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function fieldLabel(): Context\FieldLabelContext
		{
		    $localContext = new Context\FieldLabelContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 12, self::RULE_fieldLabel);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(151);

		        $_la = $this->input->LA(1);

		        if (!($_la === self::OPTIONAL || $_la === self::REPEATED)) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function field(): Context\FieldContext
		{
		    $localContext = new Context\FieldContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 14, self::RULE_field);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(154);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 5, $this->ctx)) {
		            case 1:
		        	    $this->setState(153);
		        	    $this->fieldLabel();
		        	break;
		        }
		        $this->setState(156);
		        $this->type_();
		        $this->setState(157);
		        $this->fieldName();
		        $this->setState(158);
		        $this->match(self::EQ);
		        $this->setState(159);
		        $this->fieldNumber();
		        $this->setState(164);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::LB) {
		        	$this->setState(160);
		        	$this->match(self::LB);
		        	$this->setState(161);
		        	$this->fieldOptions();
		        	$this->setState(162);
		        	$this->match(self::RB);
		        }
		        $this->setState(166);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function fieldOptions(): Context\FieldOptionsContext
		{
		    $localContext = new Context\FieldOptionsContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 16, self::RULE_fieldOptions);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(168);
		        $this->fieldOption();
		        $this->setState(173);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::COMMA) {
		        	$this->setState(169);
		        	$this->match(self::COMMA);
		        	$this->setState(170);
		        	$this->fieldOption();
		        	$this->setState(175);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function fieldOption(): Context\FieldOptionContext
		{
		    $localContext = new Context\FieldOptionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 18, self::RULE_fieldOption);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(176);
		        $this->optionName();
		        $this->setState(177);
		        $this->match(self::EQ);
		        $this->setState(178);
		        $this->constant();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function fieldNumber(): Context\FieldNumberContext
		{
		    $localContext = new Context\FieldNumberContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 20, self::RULE_fieldNumber);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(180);
		        $this->intLit();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function oneof(): Context\OneofContext
		{
		    $localContext = new Context\OneofContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 22, self::RULE_oneof);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(182);
		        $this->match(self::ONEOF);
		        $this->setState(183);
		        $this->oneofName();
		        $this->setState(184);
		        $this->match(self::LC);
		        $this->setState(190);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 162411405159432190) !== 0)) {
		        	$this->setState(188);
		        	$this->errorHandler->sync($this);

		        	switch ($this->getInterpreter()->adaptivePredict($this->input, 8, $this->ctx)) {
		        		case 1:
		        		    $this->setState(185);
		        		    $this->optionStatement();
		        		break;

		        		case 2:
		        		    $this->setState(186);
		        		    $this->oneofField();
		        		break;

		        		case 3:
		        		    $this->setState(187);
		        		    $this->emptyStatement_();
		        		break;
		        	}
		        	$this->setState(192);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(193);
		        $this->match(self::RC);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function oneofField(): Context\OneofFieldContext
		{
		    $localContext = new Context\OneofFieldContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 24, self::RULE_oneofField);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(195);
		        $this->type_();
		        $this->setState(196);
		        $this->fieldName();
		        $this->setState(197);
		        $this->match(self::EQ);
		        $this->setState(198);
		        $this->fieldNumber();
		        $this->setState(203);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::LB) {
		        	$this->setState(199);
		        	$this->match(self::LB);
		        	$this->setState(200);
		        	$this->fieldOptions();
		        	$this->setState(201);
		        	$this->match(self::RB);
		        }
		        $this->setState(205);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function mapField(): Context\MapFieldContext
		{
		    $localContext = new Context\MapFieldContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 26, self::RULE_mapField);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(207);
		        $this->match(self::MAP);
		        $this->setState(208);
		        $this->match(self::LT);
		        $this->setState(209);
		        $this->keyType();
		        $this->setState(210);
		        $this->match(self::COMMA);
		        $this->setState(211);
		        $this->type_();
		        $this->setState(212);
		        $this->match(self::GT);
		        $this->setState(213);
		        $this->mapName();
		        $this->setState(214);
		        $this->match(self::EQ);
		        $this->setState(215);
		        $this->fieldNumber();
		        $this->setState(220);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::LB) {
		        	$this->setState(216);
		        	$this->match(self::LB);
		        	$this->setState(217);
		        	$this->fieldOptions();
		        	$this->setState(218);
		        	$this->match(self::RB);
		        }
		        $this->setState(222);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function keyType(): Context\KeyTypeContext
		{
		    $localContext = new Context\KeyTypeContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 28, self::RULE_keyType);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(224);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 8386560) !== 0))) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function type_(): Context\Type_Context
		{
		    $localContext = new Context\Type_Context($this->ctx, $this->getState());

		    $this->enterRule($localContext, 30, self::RULE_type_);

		    try {
		        $this->setState(243);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 12, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(226);
		        	    $this->match(self::DOUBLE);
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(227);
		        	    $this->match(self::FLOAT);
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(228);
		        	    $this->match(self::INT32);
		        	break;

		        	case 4:
		        	    $this->enterOuterAlt($localContext, 4);
		        	    $this->setState(229);
		        	    $this->match(self::INT64);
		        	break;

		        	case 5:
		        	    $this->enterOuterAlt($localContext, 5);
		        	    $this->setState(230);
		        	    $this->match(self::UINT32);
		        	break;

		        	case 6:
		        	    $this->enterOuterAlt($localContext, 6);
		        	    $this->setState(231);
		        	    $this->match(self::UINT64);
		        	break;

		        	case 7:
		        	    $this->enterOuterAlt($localContext, 7);
		        	    $this->setState(232);
		        	    $this->match(self::SINT32);
		        	break;

		        	case 8:
		        	    $this->enterOuterAlt($localContext, 8);
		        	    $this->setState(233);
		        	    $this->match(self::SINT64);
		        	break;

		        	case 9:
		        	    $this->enterOuterAlt($localContext, 9);
		        	    $this->setState(234);
		        	    $this->match(self::FIXED32);
		        	break;

		        	case 10:
		        	    $this->enterOuterAlt($localContext, 10);
		        	    $this->setState(235);
		        	    $this->match(self::FIXED64);
		        	break;

		        	case 11:
		        	    $this->enterOuterAlt($localContext, 11);
		        	    $this->setState(236);
		        	    $this->match(self::SFIXED32);
		        	break;

		        	case 12:
		        	    $this->enterOuterAlt($localContext, 12);
		        	    $this->setState(237);
		        	    $this->match(self::SFIXED64);
		        	break;

		        	case 13:
		        	    $this->enterOuterAlt($localContext, 13);
		        	    $this->setState(238);
		        	    $this->match(self::BOOL);
		        	break;

		        	case 14:
		        	    $this->enterOuterAlt($localContext, 14);
		        	    $this->setState(239);
		        	    $this->match(self::STRING);
		        	break;

		        	case 15:
		        	    $this->enterOuterAlt($localContext, 15);
		        	    $this->setState(240);
		        	    $this->match(self::BYTES);
		        	break;

		        	case 16:
		        	    $this->enterOuterAlt($localContext, 16);
		        	    $this->setState(241);
		        	    $this->messageType();
		        	break;

		        	case 17:
		        	    $this->enterOuterAlt($localContext, 17);
		        	    $this->setState(242);
		        	    $this->enumType();
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function reserved(): Context\ReservedContext
		{
		    $localContext = new Context\ReservedContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 32, self::RULE_reserved);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(245);
		        $this->match(self::RESERVED);
		        $this->setState(248);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::INT_LIT:
		            	$this->setState(246);
		            	$this->ranges();
		            	break;

		            case self::PROTO3_LIT_SINGLE:
		            case self::PROTO3_LIT_DOBULE:
		            case self::STR_LIT:
		            	$this->setState(247);
		            	$this->reservedFieldNames();
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		        $this->setState(250);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function ranges(): Context\RangesContext
		{
		    $localContext = new Context\RangesContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 34, self::RULE_ranges);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(252);
		        $this->range_();
		        $this->setState(257);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::COMMA) {
		        	$this->setState(253);
		        	$this->match(self::COMMA);
		        	$this->setState(254);
		        	$this->range_();
		        	$this->setState(259);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function range_(): Context\Range_Context
		{
		    $localContext = new Context\Range_Context($this->ctx, $this->getState());

		    $this->enterRule($localContext, 36, self::RULE_range_);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(260);
		        $this->intLit();
		        $this->setState(266);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::TO) {
		        	$this->setState(261);
		        	$this->match(self::TO);
		        	$this->setState(264);
		        	$this->errorHandler->sync($this);

		        	switch ($this->input->LA(1)) {
		        	    case self::INT_LIT:
		        	    	$this->setState(262);
		        	    	$this->intLit();
		        	    	break;

		        	    case self::MAX:
		        	    	$this->setState(263);
		        	    	$this->match(self::MAX);
		        	    	break;

		        	default:
		        		throw new NoViableAltException($this);
		        	}
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function reservedFieldNames(): Context\ReservedFieldNamesContext
		{
		    $localContext = new Context\ReservedFieldNamesContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 38, self::RULE_reservedFieldNames);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(268);
		        $this->strLit();
		        $this->setState(273);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::COMMA) {
		        	$this->setState(269);
		        	$this->match(self::COMMA);
		        	$this->setState(270);
		        	$this->strLit();
		        	$this->setState(275);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function topLevelDef(): Context\TopLevelDefContext
		{
		    $localContext = new Context\TopLevelDefContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 40, self::RULE_topLevelDef);

		    try {
		        $this->setState(280);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::MESSAGE:
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(276);
		            	$this->messageDef();
		            	break;

		            case self::ENUM:
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(277);
		            	$this->enumDef();
		            	break;

		            case self::EXTEND:
		            	$this->enterOuterAlt($localContext, 3);
		            	$this->setState(278);
		            	$this->extendDef();
		            	break;

		            case self::SERVICE:
		            	$this->enterOuterAlt($localContext, 4);
		            	$this->setState(279);
		            	$this->serviceDef();
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function enumDef(): Context\EnumDefContext
		{
		    $localContext = new Context\EnumDefContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 42, self::RULE_enumDef);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(282);
		        $this->match(self::ENUM);
		        $this->setState(283);
		        $this->enumName();
		        $this->setState(284);
		        $this->enumBody();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function enumBody(): Context\EnumBodyContext
		{
		    $localContext = new Context\EnumBodyContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 44, self::RULE_enumBody);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(286);
		        $this->match(self::LC);
		        $this->setState(290);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 162129930182721534) !== 0)) {
		        	$this->setState(287);
		        	$this->enumElement();
		        	$this->setState(292);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(293);
		        $this->match(self::RC);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function enumElement(): Context\EnumElementContext
		{
		    $localContext = new Context\EnumElementContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 46, self::RULE_enumElement);

		    try {
		        $this->setState(298);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 20, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(295);
		        	    $this->optionStatement();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(296);
		        	    $this->enumField();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(297);
		        	    $this->emptyStatement_();
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function enumField(): Context\EnumFieldContext
		{
		    $localContext = new Context\EnumFieldContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 48, self::RULE_enumField);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(300);
		        $this->ident();
		        $this->setState(301);
		        $this->match(self::EQ);
		        $this->setState(303);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::MINUS) {
		        	$this->setState(302);
		        	$this->match(self::MINUS);
		        }
		        $this->setState(305);
		        $this->intLit();
		        $this->setState(307);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::LB) {
		        	$this->setState(306);
		        	$this->enumValueOptions();
		        }
		        $this->setState(309);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function enumValueOptions(): Context\EnumValueOptionsContext
		{
		    $localContext = new Context\EnumValueOptionsContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 50, self::RULE_enumValueOptions);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(311);
		        $this->match(self::LB);
		        $this->setState(312);
		        $this->enumValueOption();
		        $this->setState(317);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::COMMA) {
		        	$this->setState(313);
		        	$this->match(self::COMMA);
		        	$this->setState(314);
		        	$this->enumValueOption();
		        	$this->setState(319);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(320);
		        $this->match(self::RB);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function enumValueOption(): Context\EnumValueOptionContext
		{
		    $localContext = new Context\EnumValueOptionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 52, self::RULE_enumValueOption);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(322);
		        $this->optionName();
		        $this->setState(323);
		        $this->match(self::EQ);
		        $this->setState(324);
		        $this->constant();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function messageDef(): Context\MessageDefContext
		{
		    $localContext = new Context\MessageDefContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 54, self::RULE_messageDef);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(326);
		        $this->match(self::MESSAGE);
		        $this->setState(327);
		        $this->messageName();
		        $this->setState(328);
		        $this->messageBody();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function messageBody(): Context\MessageBodyContext
		{
		    $localContext = new Context\MessageBodyContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 56, self::RULE_messageBody);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(330);
		        $this->match(self::LC);
		        $this->setState(334);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 162411405159432190) !== 0)) {
		        	$this->setState(331);
		        	$this->messageElement();
		        	$this->setState(336);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(337);
		        $this->match(self::RC);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function messageElement(): Context\MessageElementContext
		{
		    $localContext = new Context\MessageElementContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 58, self::RULE_messageElement);

		    try {
		        $this->setState(348);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 25, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(339);
		        	    $this->field();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(340);
		        	    $this->enumDef();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(341);
		        	    $this->messageDef();
		        	break;

		        	case 4:
		        	    $this->enterOuterAlt($localContext, 4);
		        	    $this->setState(342);
		        	    $this->extendDef();
		        	break;

		        	case 5:
		        	    $this->enterOuterAlt($localContext, 5);
		        	    $this->setState(343);
		        	    $this->optionStatement();
		        	break;

		        	case 6:
		        	    $this->enterOuterAlt($localContext, 6);
		        	    $this->setState(344);
		        	    $this->oneof();
		        	break;

		        	case 7:
		        	    $this->enterOuterAlt($localContext, 7);
		        	    $this->setState(345);
		        	    $this->mapField();
		        	break;

		        	case 8:
		        	    $this->enterOuterAlt($localContext, 8);
		        	    $this->setState(346);
		        	    $this->reserved();
		        	break;

		        	case 9:
		        	    $this->enterOuterAlt($localContext, 9);
		        	    $this->setState(347);
		        	    $this->emptyStatement_();
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function extendDef(): Context\ExtendDefContext
		{
		    $localContext = new Context\ExtendDefContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 60, self::RULE_extendDef);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(350);
		        $this->match(self::EXTEND);
		        $this->setState(351);
		        $this->messageType();
		        $this->setState(352);
		        $this->match(self::LC);
		        $this->setState(357);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 162411405159432190) !== 0)) {
		        	$this->setState(355);
		        	$this->errorHandler->sync($this);

		        	switch ($this->input->LA(1)) {
		        	    case self::SYNTAX:
		        	    case self::IMPORT:
		        	    case self::WEAK:
		        	    case self::PUBLIC:
		        	    case self::PACKAGE:
		        	    case self::OPTION:
		        	    case self::OPTIONAL:
		        	    case self::REPEATED:
		        	    case self::ONEOF:
		        	    case self::MAP:
		        	    case self::INT32:
		        	    case self::INT64:
		        	    case self::UINT32:
		        	    case self::UINT64:
		        	    case self::SINT32:
		        	    case self::SINT64:
		        	    case self::FIXED32:
		        	    case self::FIXED64:
		        	    case self::SFIXED32:
		        	    case self::SFIXED64:
		        	    case self::BOOL:
		        	    case self::STRING:
		        	    case self::DOUBLE:
		        	    case self::FLOAT:
		        	    case self::BYTES:
		        	    case self::RESERVED:
		        	    case self::TO:
		        	    case self::MAX:
		        	    case self::ENUM:
		        	    case self::MESSAGE:
		        	    case self::SERVICE:
		        	    case self::EXTEND:
		        	    case self::RPC:
		        	    case self::STREAM:
		        	    case self::RETURNS:
		        	    case self::DOT:
		        	    case self::BOOL_LIT:
		        	    case self::IDENTIFIER:
		        	    	$this->setState(353);
		        	    	$this->field();
		        	    	break;

		        	    case self::SEMI:
		        	    	$this->setState(354);
		        	    	$this->emptyStatement_();
		        	    	break;

		        	default:
		        		throw new NoViableAltException($this);
		        	}
		        	$this->setState(359);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(360);
		        $this->match(self::RC);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function serviceDef(): Context\ServiceDefContext
		{
		    $localContext = new Context\ServiceDefContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 62, self::RULE_serviceDef);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(362);
		        $this->match(self::SERVICE);
		        $this->setState(363);
		        $this->serviceName();
		        $this->setState(364);
		        $this->match(self::LC);
		        $this->setState(368);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 283467841600) !== 0)) {
		        	$this->setState(365);
		        	$this->serviceElement();
		        	$this->setState(370);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(371);
		        $this->match(self::RC);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function serviceElement(): Context\ServiceElementContext
		{
		    $localContext = new Context\ServiceElementContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 64, self::RULE_serviceElement);

		    try {
		        $this->setState(376);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::OPTION:
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(373);
		            	$this->optionStatement();
		            	break;

		            case self::RPC:
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(374);
		            	$this->rpc();
		            	break;

		            case self::SEMI:
		            	$this->enterOuterAlt($localContext, 3);
		            	$this->setState(375);
		            	$this->emptyStatement_();
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function rpc(): Context\RpcContext
		{
		    $localContext = new Context\RpcContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 66, self::RULE_rpc);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(378);
		        $this->match(self::RPC);
		        $this->setState(379);
		        $this->rpcName();
		        $this->setState(380);
		        $this->match(self::LP);
		        $this->setState(382);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 30, $this->ctx)) {
		            case 1:
		        	    $this->setState(381);
		        	    $localContext->streamIn = $this->match(self::STREAM);
		        	break;
		        }

		        $this->setState(384);
		        $localContext->messageTypeIn = $this->messageType();
		        $this->setState(385);
		        $this->match(self::RP);
		        $this->setState(386);
		        $this->match(self::RETURNS);
		        $this->setState(387);
		        $this->match(self::LP);
		        $this->setState(389);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 31, $this->ctx)) {
		            case 1:
		        	    $this->setState(388);
		        	    $localContext->streamOut = $this->match(self::STREAM);
		        	break;
		        }

		        $this->setState(391);
		        $localContext->messageTypeOut = $this->messageType();
		        $this->setState(392);
		        $this->match(self::RP);
		        $this->setState(403);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::LC:
		            	$this->setState(393);
		            	$this->match(self::LC);
		            	$this->setState(398);
		            	$this->errorHandler->sync($this);

		            	$_la = $this->input->LA(1);
		            	while ($_la === self::OPTION || $_la === self::SEMI) {
		            		$this->setState(396);
		            		$this->errorHandler->sync($this);

		            		switch ($this->input->LA(1)) {
		            		    case self::OPTION:
		            		    	$this->setState(394);
		            		    	$this->optionStatement();
		            		    	break;

		            		    case self::SEMI:
		            		    	$this->setState(395);
		            		    	$this->emptyStatement_();
		            		    	break;

		            		default:
		            			throw new NoViableAltException($this);
		            		}
		            		$this->setState(400);
		            		$this->errorHandler->sync($this);
		            		$_la = $this->input->LA(1);
		            	}
		            	$this->setState(401);
		            	$this->match(self::RC);
		            	break;

		            case self::SEMI:
		            	$this->setState(402);
		            	$this->match(self::SEMI);
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function constant(): Context\ConstantContext
		{
		    $localContext = new Context\ConstantContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 68, self::RULE_constant);

		    try {
		        $this->setState(417);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 37, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(405);
		        	    $this->fullIdent();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(407);
		        	    $this->errorHandler->sync($this);
		        	    $_la = $this->input->LA(1);

		        	    if ($_la === self::PLUS || $_la === self::MINUS) {
		        	    	$this->setState(406);

		        	    	$_la = $this->input->LA(1);

		        	    	if (!($_la === self::PLUS || $_la === self::MINUS)) {
		        	    	$this->errorHandler->recoverInline($this);
		        	    	} else {
		        	    		if ($this->input->LA(1) === Token::EOF) {
		        	    		    $this->matchedEOF = true;
		        	    	    }

		        	    		$this->errorHandler->reportMatch($this);
		        	    		$this->consume();
		        	    	}
		        	    }
		        	    $this->setState(409);
		        	    $this->intLit();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(411);
		        	    $this->errorHandler->sync($this);
		        	    $_la = $this->input->LA(1);

		        	    if ($_la === self::PLUS || $_la === self::MINUS) {
		        	    	$this->setState(410);

		        	    	$_la = $this->input->LA(1);

		        	    	if (!($_la === self::PLUS || $_la === self::MINUS)) {
		        	    	$this->errorHandler->recoverInline($this);
		        	    	} else {
		        	    		if ($this->input->LA(1) === Token::EOF) {
		        	    		    $this->matchedEOF = true;
		        	    	    }

		        	    		$this->errorHandler->reportMatch($this);
		        	    		$this->consume();
		        	    	}
		        	    }
		        	    $this->setState(413);
		        	    $this->floatLit();
		        	break;

		        	case 4:
		        	    $this->enterOuterAlt($localContext, 4);
		        	    $this->setState(414);
		        	    $this->strLit();
		        	break;

		        	case 5:
		        	    $this->enterOuterAlt($localContext, 5);
		        	    $this->setState(415);
		        	    $this->boolLit();
		        	break;

		        	case 6:
		        	    $this->enterOuterAlt($localContext, 6);
		        	    $this->setState(416);
		        	    $this->blockLit();
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function blockLit(): Context\BlockLitContext
		{
		    $localContext = new Context\BlockLitContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 70, self::RULE_blockLit);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(419);
		        $this->match(self::LC);
		        $this->setState(426);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 162129655304814590) !== 0)) {
		        	$this->setState(420);
		        	$this->ident();
		        	$this->setState(421);
		        	$this->match(self::COLON);
		        	$this->setState(422);
		        	$this->constant();
		        	$this->setState(428);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(429);
		        $this->match(self::RC);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function emptyStatement_(): Context\EmptyStatement_Context
		{
		    $localContext = new Context\EmptyStatement_Context($this->ctx, $this->getState());

		    $this->enterRule($localContext, 72, self::RULE_emptyStatement_);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(431);
		        $this->match(self::SEMI);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function ident(): Context\IdentContext
		{
		    $localContext = new Context\IdentContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 74, self::RULE_ident);

		    try {
		        $this->setState(435);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::IDENTIFIER:
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(433);
		            	$this->match(self::IDENTIFIER);
		            	break;

		            case self::SYNTAX:
		            case self::IMPORT:
		            case self::WEAK:
		            case self::PUBLIC:
		            case self::PACKAGE:
		            case self::OPTION:
		            case self::OPTIONAL:
		            case self::REPEATED:
		            case self::ONEOF:
		            case self::MAP:
		            case self::INT32:
		            case self::INT64:
		            case self::UINT32:
		            case self::UINT64:
		            case self::SINT32:
		            case self::SINT64:
		            case self::FIXED32:
		            case self::FIXED64:
		            case self::SFIXED32:
		            case self::SFIXED64:
		            case self::BOOL:
		            case self::STRING:
		            case self::DOUBLE:
		            case self::FLOAT:
		            case self::BYTES:
		            case self::RESERVED:
		            case self::TO:
		            case self::MAX:
		            case self::ENUM:
		            case self::MESSAGE:
		            case self::SERVICE:
		            case self::EXTEND:
		            case self::RPC:
		            case self::STREAM:
		            case self::RETURNS:
		            case self::BOOL_LIT:
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(434);
		            	$this->keywords();
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function fullIdent(): Context\FullIdentContext
		{
		    $localContext = new Context\FullIdentContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 76, self::RULE_fullIdent);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(437);
		        $this->ident();
		        $this->setState(442);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::DOT) {
		        	$this->setState(438);
		        	$this->match(self::DOT);
		        	$this->setState(439);
		        	$this->ident();
		        	$this->setState(444);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function messageName(): Context\MessageNameContext
		{
		    $localContext = new Context\MessageNameContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 78, self::RULE_messageName);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(445);
		        $this->ident();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function enumName(): Context\EnumNameContext
		{
		    $localContext = new Context\EnumNameContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 80, self::RULE_enumName);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(447);
		        $this->ident();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function fieldName(): Context\FieldNameContext
		{
		    $localContext = new Context\FieldNameContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 82, self::RULE_fieldName);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(449);
		        $this->ident();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function oneofName(): Context\OneofNameContext
		{
		    $localContext = new Context\OneofNameContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 84, self::RULE_oneofName);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(451);
		        $this->ident();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function mapName(): Context\MapNameContext
		{
		    $localContext = new Context\MapNameContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 86, self::RULE_mapName);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(453);
		        $this->ident();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function serviceName(): Context\ServiceNameContext
		{
		    $localContext = new Context\ServiceNameContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 88, self::RULE_serviceName);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(455);
		        $this->ident();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function rpcName(): Context\RpcNameContext
		{
		    $localContext = new Context\RpcNameContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 90, self::RULE_rpcName);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(457);
		        $this->ident();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function messageType(): Context\MessageTypeContext
		{
		    $localContext = new Context\MessageTypeContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 92, self::RULE_messageType);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(460);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::DOT) {
		        	$this->setState(459);
		        	$this->match(self::DOT);
		        }
		        $this->setState(467);
		        $this->errorHandler->sync($this);

		        $alt = $this->getInterpreter()->adaptivePredict($this->input, 42, $this->ctx);

		        while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
		        	if ($alt === 1) {
		        		$this->setState(462);
		        		$this->ident();
		        		$this->setState(463);
		        		$this->match(self::DOT); 
		        	}

		        	$this->setState(469);
		        	$this->errorHandler->sync($this);

		        	$alt = $this->getInterpreter()->adaptivePredict($this->input, 42, $this->ctx);
		        }
		        $this->setState(470);
		        $this->messageName();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function enumType(): Context\EnumTypeContext
		{
		    $localContext = new Context\EnumTypeContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 94, self::RULE_enumType);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(473);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::DOT) {
		        	$this->setState(472);
		        	$this->match(self::DOT);
		        }
		        $this->setState(480);
		        $this->errorHandler->sync($this);

		        $alt = $this->getInterpreter()->adaptivePredict($this->input, 44, $this->ctx);

		        while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
		        	if ($alt === 1) {
		        		$this->setState(475);
		        		$this->ident();
		        		$this->setState(476);
		        		$this->match(self::DOT); 
		        	}

		        	$this->setState(482);
		        	$this->errorHandler->sync($this);

		        	$alt = $this->getInterpreter()->adaptivePredict($this->input, 44, $this->ctx);
		        }
		        $this->setState(483);
		        $this->enumName();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function intLit(): Context\IntLitContext
		{
		    $localContext = new Context\IntLitContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 96, self::RULE_intLit);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(485);
		        $this->match(self::INT_LIT);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function strLit(): Context\StrLitContext
		{
		    $localContext = new Context\StrLitContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 98, self::RULE_strLit);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(487);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 9007405413171200) !== 0))) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function boolLit(): Context\BoolLitContext
		{
		    $localContext = new Context\BoolLitContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 100, self::RULE_boolLit);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(489);
		        $this->match(self::BOOL_LIT);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function floatLit(): Context\FloatLitContext
		{
		    $localContext = new Context\FloatLitContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 102, self::RULE_floatLit);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(491);
		        $this->match(self::FLOAT_LIT);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function keywords(): Context\KeywordsContext
		{
		    $localContext = new Context\KeywordsContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 104, self::RULE_keywords);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(493);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 18014467228958718) !== 0))) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}
	}
}

namespace Prototype\Compiler\Internal\Parser\Context {
	use Antlr\Antlr4\Runtime\ParserRuleContext;
	use Antlr\Antlr4\Runtime\Token;
	use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;
	use Antlr\Antlr4\Runtime\Tree\TerminalNode;
	use Antlr\Antlr4\Runtime\Tree\ParseTreeListener;
	use Prototype\Compiler\Internal\Parser\Protobuf3Parser;
	use Prototype\Compiler\Internal\Parser\Protobuf3Visitor;

	class ProtoContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_proto;
	    }

	    public function syntax(): ?SyntaxContext
	    {
	    	return $this->getTypedRuleContext(SyntaxContext::class, 0);
	    }

	    public function EOF(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EOF, 0);
	    }

	    /**
	     * @return array<ImportStatementContext>|ImportStatementContext|null
	     */
	    public function importStatement(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ImportStatementContext::class);
	    	}

	        return $this->getTypedRuleContext(ImportStatementContext::class, $index);
	    }

	    /**
	     * @return array<PackageStatementContext>|PackageStatementContext|null
	     */
	    public function packageStatement(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(PackageStatementContext::class);
	    	}

	        return $this->getTypedRuleContext(PackageStatementContext::class, $index);
	    }

	    /**
	     * @return array<OptionStatementContext>|OptionStatementContext|null
	     */
	    public function optionStatement(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(OptionStatementContext::class);
	    	}

	        return $this->getTypedRuleContext(OptionStatementContext::class, $index);
	    }

	    /**
	     * @return array<TopLevelDefContext>|TopLevelDefContext|null
	     */
	    public function topLevelDef(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(TopLevelDefContext::class);
	    	}

	        return $this->getTypedRuleContext(TopLevelDefContext::class, $index);
	    }

	    /**
	     * @return array<EmptyStatement_Context>|EmptyStatement_Context|null
	     */
	    public function emptyStatement_(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(EmptyStatement_Context::class);
	    	}

	        return $this->getTypedRuleContext(EmptyStatement_Context::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitProto($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class SyntaxContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_syntax;
	    }

	    public function SYNTAX(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SYNTAX, 0);
	    }

	    public function EQ(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EQ, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

	    public function PROTO3_LIT_SINGLE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PROTO3_LIT_SINGLE, 0);
	    }

	    public function PROTO3_LIT_DOBULE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PROTO3_LIT_DOBULE, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitSyntax($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ImportStatementContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_importStatement;
	    }

	    public function IMPORT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::IMPORT, 0);
	    }

	    public function strLit(): ?StrLitContext
	    {
	    	return $this->getTypedRuleContext(StrLitContext::class, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

	    public function WEAK(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::WEAK, 0);
	    }

	    public function PUBLIC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PUBLIC, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitImportStatement($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class PackageStatementContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_packageStatement;
	    }

	    public function PACKAGE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PACKAGE, 0);
	    }

	    public function fullIdent(): ?FullIdentContext
	    {
	    	return $this->getTypedRuleContext(FullIdentContext::class, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitPackageStatement($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class OptionStatementContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_optionStatement;
	    }

	    public function OPTION(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::OPTION, 0);
	    }

	    public function optionName(): ?OptionNameContext
	    {
	    	return $this->getTypedRuleContext(OptionNameContext::class, 0);
	    }

	    public function EQ(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EQ, 0);
	    }

	    public function constant(): ?ConstantContext
	    {
	    	return $this->getTypedRuleContext(ConstantContext::class, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitOptionStatement($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class OptionNameContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_optionName;
	    }

	    /**
	     * @return array<FullIdentContext>|FullIdentContext|null
	     */
	    public function fullIdent(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(FullIdentContext::class);
	    	}

	        return $this->getTypedRuleContext(FullIdentContext::class, $index);
	    }

	    public function LP(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LP, 0);
	    }

	    public function RP(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RP, 0);
	    }

	    public function DOT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::DOT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitOptionName($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FieldLabelContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_fieldLabel;
	    }

	    public function OPTIONAL(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::OPTIONAL, 0);
	    }

	    public function REPEATED(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::REPEATED, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitFieldLabel($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FieldContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_field;
	    }

	    public function type_(): ?Type_Context
	    {
	    	return $this->getTypedRuleContext(Type_Context::class, 0);
	    }

	    public function fieldName(): ?FieldNameContext
	    {
	    	return $this->getTypedRuleContext(FieldNameContext::class, 0);
	    }

	    public function EQ(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EQ, 0);
	    }

	    public function fieldNumber(): ?FieldNumberContext
	    {
	    	return $this->getTypedRuleContext(FieldNumberContext::class, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

	    public function fieldLabel(): ?FieldLabelContext
	    {
	    	return $this->getTypedRuleContext(FieldLabelContext::class, 0);
	    }

	    public function LB(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LB, 0);
	    }

	    public function fieldOptions(): ?FieldOptionsContext
	    {
	    	return $this->getTypedRuleContext(FieldOptionsContext::class, 0);
	    }

	    public function RB(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RB, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitField($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FieldOptionsContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_fieldOptions;
	    }

	    /**
	     * @return array<FieldOptionContext>|FieldOptionContext|null
	     */
	    public function fieldOption(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(FieldOptionContext::class);
	    	}

	        return $this->getTypedRuleContext(FieldOptionContext::class, $index);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function COMMA(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::COMMA);
	    	}

	        return $this->getToken(Protobuf3Parser::COMMA, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitFieldOptions($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FieldOptionContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_fieldOption;
	    }

	    public function optionName(): ?OptionNameContext
	    {
	    	return $this->getTypedRuleContext(OptionNameContext::class, 0);
	    }

	    public function EQ(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EQ, 0);
	    }

	    public function constant(): ?ConstantContext
	    {
	    	return $this->getTypedRuleContext(ConstantContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitFieldOption($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FieldNumberContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_fieldNumber;
	    }

	    public function intLit(): ?IntLitContext
	    {
	    	return $this->getTypedRuleContext(IntLitContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitFieldNumber($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class OneofContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_oneof;
	    }

	    public function ONEOF(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::ONEOF, 0);
	    }

	    public function oneofName(): ?OneofNameContext
	    {
	    	return $this->getTypedRuleContext(OneofNameContext::class, 0);
	    }

	    public function LC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LC, 0);
	    }

	    public function RC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RC, 0);
	    }

	    /**
	     * @return array<OptionStatementContext>|OptionStatementContext|null
	     */
	    public function optionStatement(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(OptionStatementContext::class);
	    	}

	        return $this->getTypedRuleContext(OptionStatementContext::class, $index);
	    }

	    /**
	     * @return array<OneofFieldContext>|OneofFieldContext|null
	     */
	    public function oneofField(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(OneofFieldContext::class);
	    	}

	        return $this->getTypedRuleContext(OneofFieldContext::class, $index);
	    }

	    /**
	     * @return array<EmptyStatement_Context>|EmptyStatement_Context|null
	     */
	    public function emptyStatement_(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(EmptyStatement_Context::class);
	    	}

	        return $this->getTypedRuleContext(EmptyStatement_Context::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitOneof($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class OneofFieldContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_oneofField;
	    }

	    public function type_(): ?Type_Context
	    {
	    	return $this->getTypedRuleContext(Type_Context::class, 0);
	    }

	    public function fieldName(): ?FieldNameContext
	    {
	    	return $this->getTypedRuleContext(FieldNameContext::class, 0);
	    }

	    public function EQ(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EQ, 0);
	    }

	    public function fieldNumber(): ?FieldNumberContext
	    {
	    	return $this->getTypedRuleContext(FieldNumberContext::class, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

	    public function LB(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LB, 0);
	    }

	    public function fieldOptions(): ?FieldOptionsContext
	    {
	    	return $this->getTypedRuleContext(FieldOptionsContext::class, 0);
	    }

	    public function RB(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RB, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitOneofField($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class MapFieldContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_mapField;
	    }

	    public function MAP(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::MAP, 0);
	    }

	    public function LT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LT, 0);
	    }

	    public function keyType(): ?KeyTypeContext
	    {
	    	return $this->getTypedRuleContext(KeyTypeContext::class, 0);
	    }

	    public function COMMA(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::COMMA, 0);
	    }

	    public function type_(): ?Type_Context
	    {
	    	return $this->getTypedRuleContext(Type_Context::class, 0);
	    }

	    public function GT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::GT, 0);
	    }

	    public function mapName(): ?MapNameContext
	    {
	    	return $this->getTypedRuleContext(MapNameContext::class, 0);
	    }

	    public function EQ(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EQ, 0);
	    }

	    public function fieldNumber(): ?FieldNumberContext
	    {
	    	return $this->getTypedRuleContext(FieldNumberContext::class, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

	    public function LB(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LB, 0);
	    }

	    public function fieldOptions(): ?FieldOptionsContext
	    {
	    	return $this->getTypedRuleContext(FieldOptionsContext::class, 0);
	    }

	    public function RB(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RB, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitMapField($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class KeyTypeContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_keyType;
	    }

	    public function INT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::INT32, 0);
	    }

	    public function INT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::INT64, 0);
	    }

	    public function UINT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::UINT32, 0);
	    }

	    public function UINT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::UINT64, 0);
	    }

	    public function SINT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SINT32, 0);
	    }

	    public function SINT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SINT64, 0);
	    }

	    public function FIXED32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FIXED32, 0);
	    }

	    public function FIXED64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FIXED64, 0);
	    }

	    public function SFIXED32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SFIXED32, 0);
	    }

	    public function SFIXED64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SFIXED64, 0);
	    }

	    public function BOOL(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::BOOL, 0);
	    }

	    public function STRING(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::STRING, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitKeyType($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Type_Context extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_type_;
	    }

	    public function DOUBLE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::DOUBLE, 0);
	    }

	    public function FLOAT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FLOAT, 0);
	    }

	    public function INT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::INT32, 0);
	    }

	    public function INT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::INT64, 0);
	    }

	    public function UINT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::UINT32, 0);
	    }

	    public function UINT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::UINT64, 0);
	    }

	    public function SINT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SINT32, 0);
	    }

	    public function SINT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SINT64, 0);
	    }

	    public function FIXED32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FIXED32, 0);
	    }

	    public function FIXED64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FIXED64, 0);
	    }

	    public function SFIXED32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SFIXED32, 0);
	    }

	    public function SFIXED64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SFIXED64, 0);
	    }

	    public function BOOL(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::BOOL, 0);
	    }

	    public function STRING(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::STRING, 0);
	    }

	    public function BYTES(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::BYTES, 0);
	    }

	    public function messageType(): ?MessageTypeContext
	    {
	    	return $this->getTypedRuleContext(MessageTypeContext::class, 0);
	    }

	    public function enumType(): ?EnumTypeContext
	    {
	    	return $this->getTypedRuleContext(EnumTypeContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitType_($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ReservedContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_reserved;
	    }

	    public function RESERVED(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RESERVED, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

	    public function ranges(): ?RangesContext
	    {
	    	return $this->getTypedRuleContext(RangesContext::class, 0);
	    }

	    public function reservedFieldNames(): ?ReservedFieldNamesContext
	    {
	    	return $this->getTypedRuleContext(ReservedFieldNamesContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitReserved($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class RangesContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_ranges;
	    }

	    /**
	     * @return array<Range_Context>|Range_Context|null
	     */
	    public function range_(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(Range_Context::class);
	    	}

	        return $this->getTypedRuleContext(Range_Context::class, $index);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function COMMA(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::COMMA);
	    	}

	        return $this->getToken(Protobuf3Parser::COMMA, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitRanges($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Range_Context extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_range_;
	    }

	    /**
	     * @return array<IntLitContext>|IntLitContext|null
	     */
	    public function intLit(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(IntLitContext::class);
	    	}

	        return $this->getTypedRuleContext(IntLitContext::class, $index);
	    }

	    public function TO(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::TO, 0);
	    }

	    public function MAX(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::MAX, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitRange_($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ReservedFieldNamesContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_reservedFieldNames;
	    }

	    /**
	     * @return array<StrLitContext>|StrLitContext|null
	     */
	    public function strLit(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(StrLitContext::class);
	    	}

	        return $this->getTypedRuleContext(StrLitContext::class, $index);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function COMMA(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::COMMA);
	    	}

	        return $this->getToken(Protobuf3Parser::COMMA, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitReservedFieldNames($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class TopLevelDefContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_topLevelDef;
	    }

	    public function messageDef(): ?MessageDefContext
	    {
	    	return $this->getTypedRuleContext(MessageDefContext::class, 0);
	    }

	    public function enumDef(): ?EnumDefContext
	    {
	    	return $this->getTypedRuleContext(EnumDefContext::class, 0);
	    }

	    public function extendDef(): ?ExtendDefContext
	    {
	    	return $this->getTypedRuleContext(ExtendDefContext::class, 0);
	    }

	    public function serviceDef(): ?ServiceDefContext
	    {
	    	return $this->getTypedRuleContext(ServiceDefContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitTopLevelDef($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EnumDefContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_enumDef;
	    }

	    public function ENUM(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::ENUM, 0);
	    }

	    public function enumName(): ?EnumNameContext
	    {
	    	return $this->getTypedRuleContext(EnumNameContext::class, 0);
	    }

	    public function enumBody(): ?EnumBodyContext
	    {
	    	return $this->getTypedRuleContext(EnumBodyContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEnumDef($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EnumBodyContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_enumBody;
	    }

	    public function LC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LC, 0);
	    }

	    public function RC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RC, 0);
	    }

	    /**
	     * @return array<EnumElementContext>|EnumElementContext|null
	     */
	    public function enumElement(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(EnumElementContext::class);
	    	}

	        return $this->getTypedRuleContext(EnumElementContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEnumBody($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EnumElementContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_enumElement;
	    }

	    public function optionStatement(): ?OptionStatementContext
	    {
	    	return $this->getTypedRuleContext(OptionStatementContext::class, 0);
	    }

	    public function enumField(): ?EnumFieldContext
	    {
	    	return $this->getTypedRuleContext(EnumFieldContext::class, 0);
	    }

	    public function emptyStatement_(): ?EmptyStatement_Context
	    {
	    	return $this->getTypedRuleContext(EmptyStatement_Context::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEnumElement($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EnumFieldContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_enumField;
	    }

	    public function ident(): ?IdentContext
	    {
	    	return $this->getTypedRuleContext(IdentContext::class, 0);
	    }

	    public function EQ(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EQ, 0);
	    }

	    public function intLit(): ?IntLitContext
	    {
	    	return $this->getTypedRuleContext(IntLitContext::class, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

	    public function MINUS(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::MINUS, 0);
	    }

	    public function enumValueOptions(): ?EnumValueOptionsContext
	    {
	    	return $this->getTypedRuleContext(EnumValueOptionsContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEnumField($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EnumValueOptionsContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_enumValueOptions;
	    }

	    public function LB(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LB, 0);
	    }

	    /**
	     * @return array<EnumValueOptionContext>|EnumValueOptionContext|null
	     */
	    public function enumValueOption(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(EnumValueOptionContext::class);
	    	}

	        return $this->getTypedRuleContext(EnumValueOptionContext::class, $index);
	    }

	    public function RB(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RB, 0);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function COMMA(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::COMMA);
	    	}

	        return $this->getToken(Protobuf3Parser::COMMA, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEnumValueOptions($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EnumValueOptionContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_enumValueOption;
	    }

	    public function optionName(): ?OptionNameContext
	    {
	    	return $this->getTypedRuleContext(OptionNameContext::class, 0);
	    }

	    public function EQ(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EQ, 0);
	    }

	    public function constant(): ?ConstantContext
	    {
	    	return $this->getTypedRuleContext(ConstantContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEnumValueOption($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class MessageDefContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_messageDef;
	    }

	    public function MESSAGE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::MESSAGE, 0);
	    }

	    public function messageName(): ?MessageNameContext
	    {
	    	return $this->getTypedRuleContext(MessageNameContext::class, 0);
	    }

	    public function messageBody(): ?MessageBodyContext
	    {
	    	return $this->getTypedRuleContext(MessageBodyContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitMessageDef($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class MessageBodyContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_messageBody;
	    }

	    public function LC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LC, 0);
	    }

	    public function RC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RC, 0);
	    }

	    /**
	     * @return array<MessageElementContext>|MessageElementContext|null
	     */
	    public function messageElement(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(MessageElementContext::class);
	    	}

	        return $this->getTypedRuleContext(MessageElementContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitMessageBody($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class MessageElementContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_messageElement;
	    }

	    public function field(): ?FieldContext
	    {
	    	return $this->getTypedRuleContext(FieldContext::class, 0);
	    }

	    public function enumDef(): ?EnumDefContext
	    {
	    	return $this->getTypedRuleContext(EnumDefContext::class, 0);
	    }

	    public function messageDef(): ?MessageDefContext
	    {
	    	return $this->getTypedRuleContext(MessageDefContext::class, 0);
	    }

	    public function extendDef(): ?ExtendDefContext
	    {
	    	return $this->getTypedRuleContext(ExtendDefContext::class, 0);
	    }

	    public function optionStatement(): ?OptionStatementContext
	    {
	    	return $this->getTypedRuleContext(OptionStatementContext::class, 0);
	    }

	    public function oneof(): ?OneofContext
	    {
	    	return $this->getTypedRuleContext(OneofContext::class, 0);
	    }

	    public function mapField(): ?MapFieldContext
	    {
	    	return $this->getTypedRuleContext(MapFieldContext::class, 0);
	    }

	    public function reserved(): ?ReservedContext
	    {
	    	return $this->getTypedRuleContext(ReservedContext::class, 0);
	    }

	    public function emptyStatement_(): ?EmptyStatement_Context
	    {
	    	return $this->getTypedRuleContext(EmptyStatement_Context::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitMessageElement($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ExtendDefContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_extendDef;
	    }

	    public function EXTEND(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EXTEND, 0);
	    }

	    public function messageType(): ?MessageTypeContext
	    {
	    	return $this->getTypedRuleContext(MessageTypeContext::class, 0);
	    }

	    public function LC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LC, 0);
	    }

	    public function RC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RC, 0);
	    }

	    /**
	     * @return array<FieldContext>|FieldContext|null
	     */
	    public function field(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(FieldContext::class);
	    	}

	        return $this->getTypedRuleContext(FieldContext::class, $index);
	    }

	    /**
	     * @return array<EmptyStatement_Context>|EmptyStatement_Context|null
	     */
	    public function emptyStatement_(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(EmptyStatement_Context::class);
	    	}

	        return $this->getTypedRuleContext(EmptyStatement_Context::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitExtendDef($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ServiceDefContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_serviceDef;
	    }

	    public function SERVICE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SERVICE, 0);
	    }

	    public function serviceName(): ?ServiceNameContext
	    {
	    	return $this->getTypedRuleContext(ServiceNameContext::class, 0);
	    }

	    public function LC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LC, 0);
	    }

	    public function RC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RC, 0);
	    }

	    /**
	     * @return array<ServiceElementContext>|ServiceElementContext|null
	     */
	    public function serviceElement(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ServiceElementContext::class);
	    	}

	        return $this->getTypedRuleContext(ServiceElementContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitServiceDef($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ServiceElementContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_serviceElement;
	    }

	    public function optionStatement(): ?OptionStatementContext
	    {
	    	return $this->getTypedRuleContext(OptionStatementContext::class, 0);
	    }

	    public function rpc(): ?RpcContext
	    {
	    	return $this->getTypedRuleContext(RpcContext::class, 0);
	    }

	    public function emptyStatement_(): ?EmptyStatement_Context
	    {
	    	return $this->getTypedRuleContext(EmptyStatement_Context::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitServiceElement($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class RpcContext extends ParserRuleContext
	{
		/**
		 * @var Token|null $streamIn
		 */
		public $streamIn;

		/**
		 * @var Token|null $streamOut
		 */
		public $streamOut;

		/**
		 * @var MessageTypeContext|null $messageTypeIn
		 */
		public $messageTypeIn;

		/**
		 * @var MessageTypeContext|null $messageTypeOut
		 */
		public $messageTypeOut;

		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_rpc;
	    }

	    public function RPC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RPC, 0);
	    }

	    public function rpcName(): ?RpcNameContext
	    {
	    	return $this->getTypedRuleContext(RpcNameContext::class, 0);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function LP(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::LP);
	    	}

	        return $this->getToken(Protobuf3Parser::LP, $index);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function RP(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::RP);
	    	}

	        return $this->getToken(Protobuf3Parser::RP, $index);
	    }

	    public function RETURNS(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RETURNS, 0);
	    }

	    public function LC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LC, 0);
	    }

	    public function RC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RC, 0);
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

	    /**
	     * @return array<MessageTypeContext>|MessageTypeContext|null
	     */
	    public function messageType(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(MessageTypeContext::class);
	    	}

	        return $this->getTypedRuleContext(MessageTypeContext::class, $index);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function STREAM(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::STREAM);
	    	}

	        return $this->getToken(Protobuf3Parser::STREAM, $index);
	    }

	    /**
	     * @return array<OptionStatementContext>|OptionStatementContext|null
	     */
	    public function optionStatement(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(OptionStatementContext::class);
	    	}

	        return $this->getTypedRuleContext(OptionStatementContext::class, $index);
	    }

	    /**
	     * @return array<EmptyStatement_Context>|EmptyStatement_Context|null
	     */
	    public function emptyStatement_(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(EmptyStatement_Context::class);
	    	}

	        return $this->getTypedRuleContext(EmptyStatement_Context::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitRpc($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ConstantContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_constant;
	    }

	    public function fullIdent(): ?FullIdentContext
	    {
	    	return $this->getTypedRuleContext(FullIdentContext::class, 0);
	    }

	    public function intLit(): ?IntLitContext
	    {
	    	return $this->getTypedRuleContext(IntLitContext::class, 0);
	    }

	    public function MINUS(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::MINUS, 0);
	    }

	    public function PLUS(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PLUS, 0);
	    }

	    public function floatLit(): ?FloatLitContext
	    {
	    	return $this->getTypedRuleContext(FloatLitContext::class, 0);
	    }

	    public function strLit(): ?StrLitContext
	    {
	    	return $this->getTypedRuleContext(StrLitContext::class, 0);
	    }

	    public function boolLit(): ?BoolLitContext
	    {
	    	return $this->getTypedRuleContext(BoolLitContext::class, 0);
	    }

	    public function blockLit(): ?BlockLitContext
	    {
	    	return $this->getTypedRuleContext(BlockLitContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitConstant($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class BlockLitContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_blockLit;
	    }

	    public function LC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::LC, 0);
	    }

	    public function RC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RC, 0);
	    }

	    /**
	     * @return array<IdentContext>|IdentContext|null
	     */
	    public function ident(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(IdentContext::class);
	    	}

	        return $this->getTypedRuleContext(IdentContext::class, $index);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function COLON(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::COLON);
	    	}

	        return $this->getToken(Protobuf3Parser::COLON, $index);
	    }

	    /**
	     * @return array<ConstantContext>|ConstantContext|null
	     */
	    public function constant(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ConstantContext::class);
	    	}

	        return $this->getTypedRuleContext(ConstantContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitBlockLit($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EmptyStatement_Context extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_emptyStatement_;
	    }

	    public function SEMI(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SEMI, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEmptyStatement_($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class IdentContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_ident;
	    }

	    public function IDENTIFIER(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::IDENTIFIER, 0);
	    }

	    public function keywords(): ?KeywordsContext
	    {
	    	return $this->getTypedRuleContext(KeywordsContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitIdent($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FullIdentContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_fullIdent;
	    }

	    /**
	     * @return array<IdentContext>|IdentContext|null
	     */
	    public function ident(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(IdentContext::class);
	    	}

	        return $this->getTypedRuleContext(IdentContext::class, $index);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function DOT(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::DOT);
	    	}

	        return $this->getToken(Protobuf3Parser::DOT, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitFullIdent($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class MessageNameContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_messageName;
	    }

	    public function ident(): ?IdentContext
	    {
	    	return $this->getTypedRuleContext(IdentContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitMessageName($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EnumNameContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_enumName;
	    }

	    public function ident(): ?IdentContext
	    {
	    	return $this->getTypedRuleContext(IdentContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEnumName($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FieldNameContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_fieldName;
	    }

	    public function ident(): ?IdentContext
	    {
	    	return $this->getTypedRuleContext(IdentContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitFieldName($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class OneofNameContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_oneofName;
	    }

	    public function ident(): ?IdentContext
	    {
	    	return $this->getTypedRuleContext(IdentContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitOneofName($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class MapNameContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_mapName;
	    }

	    public function ident(): ?IdentContext
	    {
	    	return $this->getTypedRuleContext(IdentContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitMapName($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ServiceNameContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_serviceName;
	    }

	    public function ident(): ?IdentContext
	    {
	    	return $this->getTypedRuleContext(IdentContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitServiceName($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class RpcNameContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_rpcName;
	    }

	    public function ident(): ?IdentContext
	    {
	    	return $this->getTypedRuleContext(IdentContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitRpcName($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class MessageTypeContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_messageType;
	    }

	    public function messageName(): ?MessageNameContext
	    {
	    	return $this->getTypedRuleContext(MessageNameContext::class, 0);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function DOT(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::DOT);
	    	}

	        return $this->getToken(Protobuf3Parser::DOT, $index);
	    }

	    /**
	     * @return array<IdentContext>|IdentContext|null
	     */
	    public function ident(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(IdentContext::class);
	    	}

	        return $this->getTypedRuleContext(IdentContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitMessageType($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EnumTypeContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_enumType;
	    }

	    public function enumName(): ?EnumNameContext
	    {
	    	return $this->getTypedRuleContext(EnumNameContext::class, 0);
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function DOT(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(Protobuf3Parser::DOT);
	    	}

	        return $this->getToken(Protobuf3Parser::DOT, $index);
	    }

	    /**
	     * @return array<IdentContext>|IdentContext|null
	     */
	    public function ident(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(IdentContext::class);
	    	}

	        return $this->getTypedRuleContext(IdentContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitEnumType($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class IntLitContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_intLit;
	    }

	    public function INT_LIT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::INT_LIT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitIntLit($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class StrLitContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_strLit;
	    }

	    public function STR_LIT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::STR_LIT, 0);
	    }

	    public function PROTO3_LIT_SINGLE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PROTO3_LIT_SINGLE, 0);
	    }

	    public function PROTO3_LIT_DOBULE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PROTO3_LIT_DOBULE, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitStrLit($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class BoolLitContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_boolLit;
	    }

	    public function BOOL_LIT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::BOOL_LIT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitBoolLit($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FloatLitContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_floatLit;
	    }

	    public function FLOAT_LIT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FLOAT_LIT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitFloatLit($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class KeywordsContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return Protobuf3Parser::RULE_keywords;
	    }

	    public function SYNTAX(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SYNTAX, 0);
	    }

	    public function IMPORT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::IMPORT, 0);
	    }

	    public function WEAK(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::WEAK, 0);
	    }

	    public function PUBLIC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PUBLIC, 0);
	    }

	    public function PACKAGE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::PACKAGE, 0);
	    }

	    public function OPTION(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::OPTION, 0);
	    }

	    public function OPTIONAL(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::OPTIONAL, 0);
	    }

	    public function REPEATED(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::REPEATED, 0);
	    }

	    public function ONEOF(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::ONEOF, 0);
	    }

	    public function MAP(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::MAP, 0);
	    }

	    public function INT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::INT32, 0);
	    }

	    public function INT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::INT64, 0);
	    }

	    public function UINT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::UINT32, 0);
	    }

	    public function UINT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::UINT64, 0);
	    }

	    public function SINT32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SINT32, 0);
	    }

	    public function SINT64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SINT64, 0);
	    }

	    public function FIXED32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FIXED32, 0);
	    }

	    public function FIXED64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FIXED64, 0);
	    }

	    public function SFIXED32(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SFIXED32, 0);
	    }

	    public function SFIXED64(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SFIXED64, 0);
	    }

	    public function BOOL(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::BOOL, 0);
	    }

	    public function STRING(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::STRING, 0);
	    }

	    public function DOUBLE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::DOUBLE, 0);
	    }

	    public function FLOAT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::FLOAT, 0);
	    }

	    public function BYTES(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::BYTES, 0);
	    }

	    public function RESERVED(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RESERVED, 0);
	    }

	    public function TO(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::TO, 0);
	    }

	    public function MAX(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::MAX, 0);
	    }

	    public function ENUM(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::ENUM, 0);
	    }

	    public function MESSAGE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::MESSAGE, 0);
	    }

	    public function SERVICE(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::SERVICE, 0);
	    }

	    public function EXTEND(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::EXTEND, 0);
	    }

	    public function RPC(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RPC, 0);
	    }

	    public function STREAM(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::STREAM, 0);
	    }

	    public function RETURNS(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::RETURNS, 0);
	    }

	    public function BOOL_LIT(): ?TerminalNode
	    {
	        return $this->getToken(Protobuf3Parser::BOOL_LIT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof Protobuf3Visitor) {
			    return $visitor->visitKeywords($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 
}