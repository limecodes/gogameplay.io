import { SET_VISITOR_STATE } from '../actions/types';

const initialState = [];

export default (state = initialState, action) => {
	switch (action.type) {
		case SET_VISITOR_STATE:
			return {
				...action.payload
			}
		default:
			return state;
	}
}