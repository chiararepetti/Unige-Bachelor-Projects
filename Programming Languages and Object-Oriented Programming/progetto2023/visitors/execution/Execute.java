package progetto2023.visitors.execution;

import java.io.PrintWriter;

import progetto2023.environments.EnvironmentException;
import progetto2023.environments.GenEnvironment;
import progetto2023.parser.ast.Block;
import progetto2023.parser.ast.Exp;
import progetto2023.parser.ast.Stmt;
import progetto2023.parser.ast.StmtSeq;
import progetto2023.parser.ast.Variable;
import progetto2023.visitors.Visitor;

import static java.util.Objects.requireNonNull;

public class Execute implements Visitor<Value> {

	private final GenEnvironment<Value> env = new GenEnvironment<>();
	private final PrintWriter printWriter; // output stream used to print values

	public Execute() {
		printWriter = new PrintWriter(System.out, true);
	}

	public Execute(PrintWriter printWriter) {
		this.printWriter = requireNonNull(printWriter);
	}

	// dynamic semantics for programs; no value returned by the visitor

	@Override
	public Value visitMyLangProg(StmtSeq stmtSeq) {
		try {
			stmtSeq.accept(this);
			// possible runtime errors
			// EnvironmentException: undefined variable
		} catch (EnvironmentException e) {
			throw new InterpreterException(e);
		}
		return null;
	}

	// dynamic semantics for statements; no value returned by the visitor

	@Override
	public Value visitAssignStmt(Variable var, Exp exp) {
		env.update(var, exp.accept(this));
		return null;
	}

	@Override
	public Value visitPrintStmt(Exp exp) {
		printWriter.println(exp.accept(this));
		return null;
	}

	@Override
	public Value visitVarStmt(Variable var, Exp exp) {
		env.dec(var, exp.accept(this));
		return null;
	}

	@Override
	public Value visitIfStmt(Exp exp, Block thenBlock, Block elseBlock) {
		if (exp.accept(this).toBool())
			thenBlock.accept(this);
		else if (elseBlock != null)
			elseBlock.accept(this);
		return null;
	}

	@Override
	public Value visitBlock(StmtSeq stmtSeq) {
		env.enterScope();
		stmtSeq.accept(this);
		env.exitScope();
		return null;
	}

	@Override
	public Value visitForEachStmt(Variable var, Exp exp, Block block) {
		env.enterScope();
		env.dec(var, new IntValue(0));
		for (int i : exp.accept(this).toVect()) {
			env.update(var, new IntValue(i));
			block.accept(this);
		}
		env.exitScope();
		return null;
	}

	// dynamic semantics for sequences of statements
	// no value returned by the visitor

	@Override
	public Value visitEmptyStmtSeq() {
		return null;
	}

	@Override
	public Value visitNonEmptyStmtSeq(Stmt first, StmtSeq rest) {
		first.accept(this);
		rest.accept(this);
		return null;
	}

	// dynamic semantics of expressions; a value is returned by the visitor
	@Override
	public Value visitAdd(Exp left, Exp right) {
		Value exp1 = left.accept(this);
		Value exp2 = right.accept(this);

		if ((exp1 instanceof IntValue) && (exp2 instanceof IntValue)) {
			// Sum of int
			int sum_int = exp1.toInt() + exp2.toInt();
			return new IntValue(sum_int);
		} 
		else if ((exp1 instanceof VectorValue) && (exp2 instanceof VectorValue)) {
			// Sum of vect
			int [] leftVector = exp1.toVect();
			int [] rightVector = exp2.toVect();
			int [] res = new int[leftVector.length];

			// check of dim
			if (leftVector.length != rightVector.length) {
				throw new InterpreterException("Different dimension");
			}

			for (int i = 0; i < leftVector.length; i++) {
				res[i] = leftVector[i] + rightVector[i];
			}
			return new VectorValue(res);
		}
		else 
			throw new InterpreterException("can't make the sum ");
	}

	@Override
	public IntValue visitIntLiteral(int value) {
		return new IntValue(value);
	}

	@Override
	public Value visitMul(Exp left, Exp right) {
		Value exp1 = left.accept(this);
		Value exp2 = right.accept(this);

		// Mul of type int 
		if (exp1 instanceof IntValue && exp2 instanceof IntValue)
			return new IntValue(exp1.toInt() * exp2.toInt()); 

		// Mul of type vect
		else if ((exp1 instanceof VectorValue) && (exp2 instanceof VectorValue)) {
			int [] v1 = exp1.toVect();
			int [] v2 = exp2.toVect();
			int res = 0;

			if (v1.length != v2.length)
				throw new InterpreterException("Vectors must have the same dimension");

			for (int i=0; i<v1.length; i++)
				res += v1[i] * v2[i];
				
			return new IntValue(res);
		} 

		// Mul of type vect & int 
		else if ((exp1 instanceof VectorValue) && (exp2 instanceof IntValue)) {
			int [] v = exp1.toVect();

			for (int i=0; i<v.length; i++)
				v[i] *= exp2.toInt();
			
			return new VectorValue(v);
		} 

		else if ((exp2 instanceof VectorValue) && (exp1 instanceof IntValue)) {
			int [] v = exp2.toVect();

			for (int i=0; i<v.length; i++)
				v[i] *= exp1.toInt();;
			
			return new VectorValue(v);
		}
		
		else
			throw new InterpreterException("can't make the multiplication");
	}


	@Override
	public IntValue visitSign(Exp exp) {
		return new IntValue(-exp.accept(this).toInt());
	}

	@Override
	public Value visitVariable(Variable var) {
		return env.lookup(var);
	}

	@Override
	public BoolValue visitNot(Exp exp) {
		return new BoolValue(!exp.accept(this).toBool());
	}

	@Override
	public BoolValue visitAnd(Exp left, Exp right) {
		return new BoolValue(left.accept(this).toBool() && right.accept(this).toBool());
	}

	@Override
	public BoolValue visitBoolLiteral(boolean value) {
		return new BoolValue(value);
	}

	@Override
	public BoolValue visitEq(Exp left, Exp right) {
		return new BoolValue(left.accept(this).equals(right.accept(this)));
	}

	@Override
	public PairValue visitPairLit(Exp left, Exp right) {
		return new PairValue(left.accept(this), right.accept(this));
	}

	@Override
	public Value visitFst(Exp exp) {
		return exp.accept(this).toPair().getFstVal();
	}

	@Override
	public Value visitSnd(Exp exp) {
		return exp.accept(this).toPair().getSndVal();
	}

	@Override
	public Value visitVectorLiteral(Exp exp1, Exp exp2) throws NegativeArraySizeException, ArrayIndexOutOfBoundsException {
		return new VectorValue(exp1.accept(this).toInt(), exp2.accept(this).toInt());
	}

}

