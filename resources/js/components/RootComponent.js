import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { setVisitorData } from '../actions/visitor';

import ChangeConnection from './ChangeConnection';

class RootComponent extends Component {
	
	constructor(props) {
		super(props);
		this.props.setVisitorData(this.props.uid, this.props.device, (this.props.connection == "") ? false : true, this.props.carrier);
	}

	render() {
		return (
	        <div className="container">
	            <div className="row justify-content-center">
	                <div className="col-12">
	                	<ChangeConnection />
	                    <div className="card-body">{ this.props.visitor.carrier }</div>
	                </div>
	            </div>
	        </div>
    	);
	}
}

const mapStateToProps = state => ({
	visitor: state.visitor
});

export default connect(mapStateToProps, { setVisitorData })(RootComponent);