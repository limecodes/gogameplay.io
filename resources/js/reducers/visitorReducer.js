import { SET_VISITOR_STATE, CONNECTION_CHANGE_SUCCESS, CONNECTION_CHANGE_FAILURE } from '../actions/types';

const initialState = {
	uid: null,
	device: null,
	connection: null,
	carrier: null,
	error: false
};

export default (state = initialState, action) => {
	switch (action.type) {
		case SET_VISITOR_STATE:
			return {
				...state,
				...action.payload
			}
		case CONNECTION_CHANGE_SUCCESS:
			return {
				...state,
				connection: (action.payload.connection == 1) ? true : false,
				carrier: action.payload.carrier
			}
		default:
			return state;
	}
}