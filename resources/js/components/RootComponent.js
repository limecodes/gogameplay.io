import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { setVisitorData } from '../actions/visitor';

import StackContainer from '../containers/StackContainer';
import StepContainer from '../containers/StepContainer';
import Instructions from './Instructions';
import Searching from './Searching';

class RootComponent extends Component {
	
	constructor(props) {
		super(props);
		this.props.setVisitorData(this.props.uid, this.props.device, (this.props.connection == "") ? false : true, this.props.carrier);
	}

	render() {
		const platformImage = 'http://static.offers.gogameplay.io/images/' + this.props.visitor.device + '.png';

		if ( (this.props.validation.platform) && (this.props.validation.carrier) && (this.props.validation.search) ) {
			return (
				<div className="container">
					<Searching />
				</div>
			);
		} else {
			return (
		        <div className="container">
		            <StepContainer />
		        </div>
    		);
		}
	}
}

const mapStateToProps = state => ({
	visitor: state.visitor,
	validation: state.validation
});

export default connect(mapStateToProps, { setVisitorData })(RootComponent);