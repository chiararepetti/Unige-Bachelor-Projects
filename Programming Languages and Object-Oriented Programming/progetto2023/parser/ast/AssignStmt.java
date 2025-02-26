package progetto2023.parser.ast;

import progetto2023.visitors.Visitor;

public class AssignStmt extends AbstractAssignStmt {

	public AssignStmt(Variable var, Exp exp) {
		super(var, exp);
	}
	
	@Override
	public <T> T accept(Visitor<T> visitor) {
		return visitor.visitAssignStmt(var, exp);
	}
}
