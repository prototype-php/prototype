<?php

/*
 * Generated from resources/grammar/Protobuf3.g4 by ANTLR 4.13.1
 */

namespace Prototype\Compiler\Internal\Parser;

use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;

/**
 * This interface defines a complete generic visitor for a parse tree produced by {@see Protobuf3Parser}.
 */
interface Protobuf3Visitor extends ParseTreeVisitor
{
	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::proto()}.
	 *
	 * @param Context\ProtoContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitProto(Context\ProtoContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::syntax()}.
	 *
	 * @param Context\SyntaxContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitSyntax(Context\SyntaxContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::importStatement()}.
	 *
	 * @param Context\ImportStatementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitImportStatement(Context\ImportStatementContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::packageStatement()}.
	 *
	 * @param Context\PackageStatementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPackageStatement(Context\PackageStatementContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::optionStatement()}.
	 *
	 * @param Context\OptionStatementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitOptionStatement(Context\OptionStatementContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::optionName()}.
	 *
	 * @param Context\OptionNameContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitOptionName(Context\OptionNameContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::fieldLabel()}.
	 *
	 * @param Context\FieldLabelContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFieldLabel(Context\FieldLabelContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::field()}.
	 *
	 * @param Context\FieldContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitField(Context\FieldContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::fieldOptions()}.
	 *
	 * @param Context\FieldOptionsContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFieldOptions(Context\FieldOptionsContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::fieldOption()}.
	 *
	 * @param Context\FieldOptionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFieldOption(Context\FieldOptionContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::fieldNumber()}.
	 *
	 * @param Context\FieldNumberContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFieldNumber(Context\FieldNumberContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::oneof()}.
	 *
	 * @param Context\OneofContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitOneof(Context\OneofContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::oneofField()}.
	 *
	 * @param Context\OneofFieldContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitOneofField(Context\OneofFieldContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::mapField()}.
	 *
	 * @param Context\MapFieldContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMapField(Context\MapFieldContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::keyType()}.
	 *
	 * @param Context\KeyTypeContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitKeyType(Context\KeyTypeContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::type_()}.
	 *
	 * @param Context\Type_Context $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitType_(Context\Type_Context $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::reserved()}.
	 *
	 * @param Context\ReservedContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitReserved(Context\ReservedContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::ranges()}.
	 *
	 * @param Context\RangesContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitRanges(Context\RangesContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::range_()}.
	 *
	 * @param Context\Range_Context $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitRange_(Context\Range_Context $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::reservedFieldNames()}.
	 *
	 * @param Context\ReservedFieldNamesContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitReservedFieldNames(Context\ReservedFieldNamesContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::topLevelDef()}.
	 *
	 * @param Context\TopLevelDefContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitTopLevelDef(Context\TopLevelDefContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::enumDef()}.
	 *
	 * @param Context\EnumDefContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEnumDef(Context\EnumDefContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::enumBody()}.
	 *
	 * @param Context\EnumBodyContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEnumBody(Context\EnumBodyContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::enumElement()}.
	 *
	 * @param Context\EnumElementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEnumElement(Context\EnumElementContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::enumField()}.
	 *
	 * @param Context\EnumFieldContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEnumField(Context\EnumFieldContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::enumValueOptions()}.
	 *
	 * @param Context\EnumValueOptionsContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEnumValueOptions(Context\EnumValueOptionsContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::enumValueOption()}.
	 *
	 * @param Context\EnumValueOptionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEnumValueOption(Context\EnumValueOptionContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::messageDef()}.
	 *
	 * @param Context\MessageDefContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMessageDef(Context\MessageDefContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::messageBody()}.
	 *
	 * @param Context\MessageBodyContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMessageBody(Context\MessageBodyContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::messageElement()}.
	 *
	 * @param Context\MessageElementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMessageElement(Context\MessageElementContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::extendDef()}.
	 *
	 * @param Context\ExtendDefContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExtendDef(Context\ExtendDefContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::serviceDef()}.
	 *
	 * @param Context\ServiceDefContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitServiceDef(Context\ServiceDefContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::serviceElement()}.
	 *
	 * @param Context\ServiceElementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitServiceElement(Context\ServiceElementContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::rpc()}.
	 *
	 * @param Context\RpcContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitRpc(Context\RpcContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::constant()}.
	 *
	 * @param Context\ConstantContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitConstant(Context\ConstantContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::blockLit()}.
	 *
	 * @param Context\BlockLitContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBlockLit(Context\BlockLitContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::emptyStatement_()}.
	 *
	 * @param Context\EmptyStatement_Context $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEmptyStatement_(Context\EmptyStatement_Context $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::ident()}.
	 *
	 * @param Context\IdentContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIdent(Context\IdentContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::fullIdent()}.
	 *
	 * @param Context\FullIdentContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFullIdent(Context\FullIdentContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::messageName()}.
	 *
	 * @param Context\MessageNameContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMessageName(Context\MessageNameContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::enumName()}.
	 *
	 * @param Context\EnumNameContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEnumName(Context\EnumNameContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::fieldName()}.
	 *
	 * @param Context\FieldNameContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFieldName(Context\FieldNameContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::oneofName()}.
	 *
	 * @param Context\OneofNameContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitOneofName(Context\OneofNameContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::mapName()}.
	 *
	 * @param Context\MapNameContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMapName(Context\MapNameContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::serviceName()}.
	 *
	 * @param Context\ServiceNameContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitServiceName(Context\ServiceNameContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::rpcName()}.
	 *
	 * @param Context\RpcNameContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitRpcName(Context\RpcNameContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::messageType()}.
	 *
	 * @param Context\MessageTypeContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMessageType(Context\MessageTypeContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::enumType()}.
	 *
	 * @param Context\EnumTypeContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitEnumType(Context\EnumTypeContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::intLit()}.
	 *
	 * @param Context\IntLitContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIntLit(Context\IntLitContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::strLit()}.
	 *
	 * @param Context\StrLitContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitStrLit(Context\StrLitContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::boolLit()}.
	 *
	 * @param Context\BoolLitContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBoolLit(Context\BoolLitContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::floatLit()}.
	 *
	 * @param Context\FloatLitContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFloatLit(Context\FloatLitContext $context);

	/**
	 * Visit a parse tree produced by {@see Protobuf3Parser::keywords()}.
	 *
	 * @param Context\KeywordsContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitKeywords(Context\KeywordsContext $context);
}