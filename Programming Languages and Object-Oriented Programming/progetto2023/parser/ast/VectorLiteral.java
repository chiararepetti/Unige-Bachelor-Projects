package progetto2023.parser.ast;

import progetto2023.visitors.Visitor;

// It extends binary operation 
public class VectorLiteral extends BinaryOp{

	public VectorLiteral(Exp left, Exp right) {
		super(left, right);
	}
	
	@Override
	public <T> T accept(Visitor<T> visitor) {
		return visitor.visitVectorLiteral(left,right);
	}
}