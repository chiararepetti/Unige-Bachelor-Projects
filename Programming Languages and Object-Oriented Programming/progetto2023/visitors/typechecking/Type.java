package progetto2023.visitors.typechecking;

import static progetto2023.visitors.typechecking.AtomicType.INT;
import static progetto2023.visitors.typechecking.AtomicType.VECTOR;;

public interface Type {
	default void checkEqual(Type found) throws TypecheckerException {
		if (!equals(found))
			throw new TypecheckerException(found.toString(), toString());
	}

	default PairType checkIsPairType() throws TypecheckerException {
		if (this instanceof PairType pt)
			return pt;
		throw new TypecheckerException(toString(), PairType.TYPE_NAME);
	}

	default Type getFstPairType() throws TypecheckerException {
		return checkIsPairType().getFstType();
	}

	default Type getSndPairType() throws TypecheckerException {
		return checkIsPairType().getSndType();
	}

	// type int or vector
	default AtomicType checkType() throws TypecheckerException{
		if (equals(INT))
			return INT;
		else if (equals(VECTOR))
			return VECTOR;
		throw new TypecheckerException(toString(), "Type incorrect");
	}
}
