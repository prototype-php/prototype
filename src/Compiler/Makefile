.PHONY: parser
parser: ## fix this command
	docker compose run --rm antlr -Dlanguage=PHP -package "Prototype\Compiler\Internal\Parser" -o Internal/Parser -visitor -no-listener resources/grammar/Protobuf3.g4
	sudo mv Internal/Parser/resources/grammar/* Internal/Parser
	sudo rm -rf Internal/Parser/resources
