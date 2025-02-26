package progetto2023.visitors.execution;


public interface Value {
	/* default conversion methods */
	default int toInt() {
		throw new InterpreterException("Expecting an integer");
	}

	default boolean toBool() {
		throw new InterpreterException("Expecting a boolean");
	}

	default PairValue toPair() {
		throw new InterpreterException("Expecting a pair");
	}

	// exp of the vector are of type int.
	default int[] toVect() {
		throw new InterpreterException("Expecting a vector");
	}
}
