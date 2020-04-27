import { SET_VISITOR_STATE, CONNECTION_CHANGE_SUCCESS } from '../actions/types';

const initialState = [];

export default (state = initialState, action) => {
	switch (action.type) {
		case SET_VISITOR_STATE:
			return {
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