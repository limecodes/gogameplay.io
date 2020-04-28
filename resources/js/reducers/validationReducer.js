import { VALIDATE_PLATFORM, VALIDATE_CARRIER, VALIDATE_SEARCH } from '../actions/types';

const initialState = {
	platform: false,
	carrier: false,
	search: false
}

export default (state = initialState, action) => {
	switch (action.type) {
		case VALIDATE_PLATFORM:
			return {
				...state,
				platform: true
			}
		case VALIDATE_CARRIER:
			return {
				...state,
				carrier: true
			}
		case VALIDATE_SEARCH:
			return {
				...state,
				search: true
			}
		default:
			return state;
	}
}