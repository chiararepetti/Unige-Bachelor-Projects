package progetto2023.parser.ast;

import progetto2023.visitors.Visitor;

public interface AST {
	<T> T accept(Visitor<T> visitor);
}
